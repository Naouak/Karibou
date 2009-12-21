#!/usr/bin/python

import lxml.etree
import os, sys, os.path

class Application:
	def __init__ (self, folder):
		self.folder = folder
		self.classes = {}	## class => file
		self.views = {}		## Name => {class: aaa, template: bbb}
		self.pages = []		## List of (name, view)
		self.hooks = []		## List of (view, hook name)
		self.sawPermissions = False
		self.templateDir = "templates"
	
	def isValid (self):
		try:
			confFile = os.path.join(self.folder, "config.xml")
			if not(os.path.isfile(confFile)):
				print "No config.xml file"
				return False
			os.chdir(self.folder)
			if not(self.parseConfigFile()):
				print "Invalid config.xml file"
				return False
			if not(self.checkViews()):
				return False
			if not(self.checkFiles()):
				return False
			os.chdir("..")
			return True
		except Exception, e:
			print e
			return False
	
	def parseConfigFile (self):
		xmlDoc = lxml.etree.parse("config.xml")
		root = xmlDoc.getroot()
		assert root.tag == "app", "Invalid root node : named '%s' instead of 'app'" % root.tag
		if not ((len(root.attrib) == 1 and root.attrib.has_key("templatedir")) or (len(root.attrib) == 0)):
			print root.attrib
			raise Exception, "Invalid root node attributes"
		if "templatedir" in root.attrib:
			self.templateDir = root.attrib["templatedir"]
		result = True
		for node in root.getchildren():
			if isinstance(node, lxml.etree._Comment):
				continue
			if not(hasattr(self, "loadNode_" + node.tag)):
				raise Exception, "Invalid child node %s" % node.tag
			else:
				result = result and getattr(self, "loadNode_" + node.tag)(node)
		return result
	
	def checkViews (self):
		# First, check the hooks
		for hook in self.hooks:
			if not(hook[1] in self.views):
				print "Missing view %s for hook %s" % (hook[1], hook[0])
				return False
		return True
	
	def checkFiles (self):
		validFiles = ["config.xml", self.folder + ".app2"]
		# First, check the classes...
		for load in self.classes.values():
			if not os.path.isfile(load):
				raise Exception, "Missing class file %s" % load
			validFiles.append(load)
		# Now, the templates
		for view in self.views.values():
			fileName = os.path.join(self.templateDir, view["template"])
			if not os.path.isfile(fileName):
				raise Exception, "Missing template file %s" % fileName
			validFiles.append(fileName)
		for fileName in os.listdir("."):
			if os.path.isdir(fileName):
				# Do something with the folders
				pass
			elif not fileName in validFiles:
				print "Useless file : %s" % fileName
				return False
		return True
	
	def loadNode_permissions(self, node):
		# Check a permissions node
		if self.sawPermissions:
			print "Only one permissions node is allowed per config.xml"
			return False
		self.sawPermissions = True
		if (len(node.getchildren()) != 0):
			print "A permissions node can't have children"
			return False
		attrs = node.attrib
		if len(attrs) != 2:
			print "Invalid number of attributes for the permissions node"
			return False
		for attrib in ["logged", "default"]:
			if not attrib in attrs:
				print "Missing '%s' attribute for the permissions node" % attrib
				return False
		return True
	
	def loadNode_hook(self, node):
		# Check a hook node
		if (len(node.getchildren()) != 0):
			print "A hook node can't have children"
			return False
		attrs = node.attrib
		if len(attrs) != 2:
			print "Invalid number of attributes for the hook node"
			return False
		for attrib in ["name", "view"]:
			if not attrib in attrs:
				print "Missing '%s' attribute for the hook node" % attrib
				return False
		self.hooks.append((attrs["name"], attrs["view"]))
		return True
	
	def loadNode_load(self, node):
		# Check a load node
		if (len(node.getchildren()) != 0):
			print "A load node can't have children"
			return False
		attrs = node.attrib
		if not len(attrs) in [1, 2] :
			print "Invalid number of attributes for the load node"
			return False
		if not("class") in attrs:
			print "Missing class attribute for a load node."
			return False
		className = attrs["class"]
		if not className.startswith("["):
			if not "file" in attrs:
				print "Missing file attribute for a load node."
				return False
		else:
			className = className[className.index("/"):]
		if className in self.classes:
			print "Duplicated load entry for %s" % className
			return False
		if "file" in attrs:
			self.classes[className] = attrs["file"]
		else:
			self.classes[className] = attrs["class"]
		return True
	
	def loadNode_header(self, node):
		# Check a header node
		if (len(node.getchildren()) != 0):
			print "A header node can't have children"
			return False
		attrs = node.attrib
		if len(attrs) != 2:
			print "Invalid number of attributes for the header node"
			return False
		for attrib in ["view", "app"]:
			if not attrib in attrs:
				print "Missing '%s' attribute for the header node" % attrib
				return False
		return True
	
	def loadNode_footer(self, node):
		# Check a footer node
		if (len(node.getchildren()) != 0):
			print "A footer node can't have children"
			return False
		attrs = node.attrib
		if len(attrs) != 2:
			print "Invalid number of attributes for the footer node"
			return False
		for attrib in ["view", "app"]:
			if not attrib in attrs:
				print "Missing '%s' attribute for the footer node" % attrib
				return False
		return True
	
	def loadNode_view(self, node):
		# Check a view node
		name = node.attrib["name"]
		if name in self.views:
			print "Duplicated view '%s'" % name
			return False
		if (len(node.getchildren()) != 0):
			print "A view node can't have children"
			return False
		attrs = node.attrib
		if not len(attrs) in [2,3]:
			print "Invalid number of attributes for the view node"
			return False
		if not "name" in attrs:
			print "Missing name attribute for a view node"
			return False
		if not "class" in attrs:
			attrs["class"] = "EmptyModel"
		if not "template" in attrs:
			attrs["template"] = ""
		self.views[attrs["name"]] = {"class": attrs["class"], "template": attrs["template"]}
		return True
	
	def loadNode_page (self, node):
		# Check a page node
		attrs = node.attrib
		if not "view" in attrs:
			print "Missing view attribute for the page node"
			return False
		pageName = ""
		if not "name" in attrs:
			#print "Warning: missing name attribute in the page node"
			pass
		else:
			pageName = attrs["name"]
			#for page in self.pages:
				#if page[0] == attrs["name"]:
					#print "Duplicated page name '%s'" % attrs["name"]
					#return False
		for attr in attrs.keys():
			if not attr in ["name", "view", "contenttype"]:
				print "Invalid attribute %s for the page node" % attr
				return False
		for subnode in node.getchildren():
			if isinstance(subnode, lxml.etree._Comment):
				continue
			if not subnode.tag in ["footer", "header", "argument", "option"]:
				print "Invalid subnode %s for the page node" % subnode.tag
				return False
		self.pages.append((pageName, attrs["view"]))
		return True
	
	def loadNode_config(self, node):
		# Check a config node
		return True
	
	def loadNode_form(self, node):
		# Check a form node
		return True

if __name__ == "__main__":
	appFolder = "."
	if len(sys.argv) == 2:
		appFolder = sys.argv[-1]
	os.chdir(appFolder)
	for folder in os.listdir("."):
		if (folder[0] == ".") or not(os.path.isdir(folder)):
			continue
		app = Application(folder)
		curFolder = os.getcwd()
		print "*" * 20, folder, "*" * 20
		if app.isValid():
			pass
			#print "%s : VALID" % folder
		else:
			print " =============> INVALID"
		os.chdir(curFolder)
