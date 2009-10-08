#! /usr/bin/env python
import os
import sys

# definition of all fonctions 

def creationofdir(dir,name):
    os.mkdir("../"+dir+"/"+name)

def creationoffile(dir,name,extension):
    fichier = open("../"+arg+dir+"/"+name+"."+extension,"w+")
    fichier.close()

def parsingoffile(filesource,filedestination,variable):
    fs = open(filesource, "r")
    fd = open("../"+filedestination,"w")

    data = fs.read()

    for name in variable:
            data = data.replace("{%s}" % name, '"%s"' % str(variable[name]))

    fd.write(data)
    fs.close()
    fd.close()

def cleaningoffile(file):
    f= open("../"+file,"r")
    data=f.read()
    lines=data.splitlines()
    
    for i in range(len(lines)):
        if "{" in lines[i]:
            lines[i]=";"+lines[i]
    
    d="\n".join(lines)
    f.close()
    f=open("../"+file,"w")
    f.write(d)
    f.close()

#we check if we are in the right directory
if (os.path.basename(os.getcwd())!="createapps"):
    print "you are not in the good path"
else:
    # check if an argument were given 
    if len(sys.argv) <2:
        print "you should have tiped an argument"
    else:
        arg = sys.argv[1]
        #check if the name is already use for the other apps
        if (os.path.exists("../"+arg)):
            print "this name is already use for an application, please choose an other one"
        else:
            # creation of all needed files and directories
            creationofdir("",arg)

            creationoffile("","config","xml")
            creationoffile("",arg,"app2")
            creationoffile("",arg,"class.php")

            creationofdir(arg,"languages")

            creationoffile("/languages","en","po")
            creationoffile("/languages","fr","po")

            creationofdir(arg,"templates")

            creationoffile("/templates","mini","tpl")

            app2 = {"name_fr" : "french name of the app","name_en" : "english name of the app", "desc_fr" : "french description of the app", "desc_en" : "english description of the app" }
            app2_response = {}
            for (name, question) in app2.items():
                app2_response[name] = raw_input(question + ":")

            app2_response["view"]="mini"

            # all files needed are created, now we are going to create optional files
            promptconfig = raw_input("do you need system of config ? ")
            if (promptconfig in ["", "yes","y", "o","oui"]):
                creationoffile("",arg+"config","class.php")
                app2_response["configmodel"]="config"

            promptsubmit = raw_input("do you need a configuration system ? ")
            if (promptsubmit in ["","yes","y","o","oui"]):
                creationoffile("",arg+"submit","class.php")
                app2_response["submitmodel"]="submit"

            promptjs = raw_input("do you want to use Javascript ? ")
            if (promptjs in ["","yes","y","o","oui"]):
                creationoffile("/templates","js","tpl")
                app2_response["jsview"]="JS"

            parsingoffile("base.app2",arg+"/"+arg+".app2",app2_response)
            cleaningoffile(arg+"/"+arg+".app2")
