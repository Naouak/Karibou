#!/usr/bin/python
# -*- coding: utf-8 -*-

import pantie

from twisted.application import internet, service

f = pantie.PantieFactory()

application = service.Application('pantie')
serviceCollection = service.IServiceCollection(application)
internet.TCPServer(5896, f).setServiceParent(serviceCollection)
