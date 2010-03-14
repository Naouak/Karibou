# JSON (from http://json-py.sourceforge.net/, not the Python 2.6 implementation)
import json

# Twisted
from twisted.internet import protocol, reactor, defer
from twisted.protocols import basic

class Event:
	"""
	Only contains datas of an event
	"""

	def __init__(self, name, data):
		"""
		Instanciate the Event with its data
		"""
		self.name = name
		self.data = data

class Session:
	"""
	Describes a session, and dispatches events
	"""

	def __init__(self):
		self.stack = []
		self.waiting = []
		self.sensitive = []

	def waitEvents(self, events, client):
		"""
		Waits for one or more event. Returns a Deferred object
		"""

		print "waiting for events %s" % events

		# Update the list of events to be sensitive to
		for evt in events:
			if evt not in self.sensitive:
				self.sensitive.append(evt)

		# Make the deferred
		d = defer.Deferred()

		# Is there a stack to empty ?
		if len(self.stack) != 0:
			send = []

			todel = []
			for evt in self.stack:
				if evt.name in events:
					send.append(evt)
					todel.append(evt)

			for evt in todel:
				self.stack.remove(evt)
			del todel

			if len(send) != 0:
				d.callback(send)
				print "-> events were on the stack"
				return d

		self.waiting.append({
			"events": events,
			"d": d,
			"client": client
		})
		return d

	def gotEvent(self, event):
		"""
		Tries to give an event to a waiting connection. If nobody is waiting, the
		event is added to the stack
		"""

		print "got event %s, added to stack %x" % (event, id(self.stack))

		# Nothing to do if the session is not sensitive to this event
		if event.name not in self.sensitive:
			return

		for w in self.waiting:
			if event.name in w["events"]:
				w["d"].callback([event])
				self.waiting.remove(w)
				return

		# Found no valid waiter, pushing the event to the stack
		self.stack.append(event)

	def clientLost(self, client):
		for w in self.waiting:
			if w["client"] == client:
				self.waiting.remove(w)

class SessionFactory:
	sessions = {}

	def getSession(self, session):
		# If the session was previously created
		if self.sessions.has_key(session):
			s = self.sessions[session]

			print "got session %s" % session

			# There is 1 more connection linked to this session
			s["count"] = s["count"] + 1

			# Nemesis no more needed
			if s["nemesis"] != None:
				s["nemesis"].cancel()
				s["nemesis"] = None
				print "session %s no more going to die" % session

			return s["object"]
		else:
			print "created session %s" % session

			self.sessions[session] = {
				"count": 1,
				"object": Session(),
				"nemesis": None,
				"allow": []
			}
			return self.sessions[session]["object"]

	def clientLost(self, session, client):
		print "%s lost was lost by session %s" % (client, session)
		if self.sessions.has_key(session):
			s = self.sessions[session]
			s["object"].clientLost(client)
			self.detachSession(session)

	def detachSession(self, session):
		if self.sessions.has_key(session):
			s = self.sessions[session]
			s["count"] = s["count"] - 1

			print "session %s has a count of %s" % (session, s["count"])

			if s["count"] == 0:
				def kill(session):
					if self.sessions.has_key(session):
						del self.sessions[session]
						print "killed session %s" % session

				# No more sessions linked, 5 minutes left to live
				print "session %s will be destroyed" % session
				s["nemesis"] = reactor.callLater(300, kill, session)

	def throwEvent(self, event, f = None):
		for name, s in self.sessions.items():
			if (f == None or name in f) and event.name in s["allow"]:
				s["object"].gotEvent(event)

	def register(self, session, event):
		print "registering session %s for event %s" % (session, event)
		s = self.getSession(session)

		s = self.sessions[session]
		if not event in s["allow"]:
			s["allow"].append(event)

		self.detachSession(session)

class Pantie(basic.Int32StringReceiver):
	"""
	The front end to talk with a client
	"""

	def __init__(self):
		self.session = None

	def stringReceived(self, jmsg):
		if self.session != None:
			return

		try:
			msg = json.read(jmsg)

			if msg["do"] == "grab":
				d = self.factory.sessionFactory.getSession(msg["by"]).waitEvents(msg["what"], self)

				self.session = msg["by"]

				print "%s grabbed events %s" % (msg["by"], msg["what"])

				# Called when one or more events occurs
				def onEvent(events):
					ans = {
						"got": "pantie",
						"drawer": []
					}

					for i in range(len(events)):
						ans["drawer"].append({
							"color": events[i].name,
							"pattern": events[i].data
						})

					self.sendString(json.write(ans))
					self.stopProducing()
				d.addCallback(onEvent)

				# Called if an error occured
				def onError():
					self.slap()
				d.addErrback(onError)

			elif msg["do"] == "wear":
				if msg.has_key("for"):
					self.factory.sessionFactory.throwEvent(Event(msg["what"], msg["how"]), msg["for"])
				else:
					self.factory.sessionFactory.throwEvent(Event(msg["what"], msg["how"]))
				self.stopProducing()
				print "event %s was received" % msg["what"]

			elif msg["do"] == "register":
				self.factory.sessionFactory.register(msg["who"], msg["for"])

		except json.ReadException:
			self.slap()
		except KeyError:
			self.slap()

	def slap(self):
		self.sendString(json.write({"got": "slap"}))
		self.stopProducing()

	def connectionLost(self, reason):
		if self.session != None:
			self.factory.sessionFactory.clientLost(self.session, self)
		print "connection lost"

class PantieFactory(protocol.ServerFactory):
	"""
	Produces instances of the Pantie protocol, and provides him with helping
	objects
	"""
	protocol = Pantie

	def __init__(self):
		"""
		Initializes the facotry
		"""
		# Create factories
		self.sessionFactory = SessionFactory()

if __name__ == "__main__":
	print "test !"
