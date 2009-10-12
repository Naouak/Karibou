#! /usr/bin/env python
import os
import sys

# definition of all fonctions 

def creationofdir(dir,name):
    os.mkdir("../"+dir+"/"+name)
    return "directory "+name+" created"

def creationoffile(dir,name,extension):
    fichier = open("../"+arg+dir+"/"+name+"."+extension,"w+")
    fichier.close()
    return "file : "+name+"."+extension+" created"

def parsingoffile(filesource,filedestination,variable):
    fs = open(filesource, "r")
    fd = open("../"+arg+"/"+filedestination,"w")

    data = fs.read()

    for name in variable:
            data = data.replace("{%s}" % name, str(variable[name]))

    fd.write(data)
    fs.close()
    fd.close()
    return "file "+filedestination+" parsed"

def cleaningoffile(file,commentaire):
    f= open("../"+arg+"/"+file,"r")
    data=f.read()
    lines=data.splitlines()
    
    for i in range(len(lines)):
        if "{" in lines[i]:
            lines[i]=commentaire+lines[i]
    
    d="\n".join(lines)
    f.close()
    f=open("../"+file,"w")
    f.write(d)
    f.close()
    return file+ " cleaned"

def prompt(what):
    prompt = raw_input("Do you need "+what+" system (Y/n)? ")
    if (prompt in ["", "yes","y", "o","oui"]):
        print "you choose the "+ what + " system"
        print creationoffile("",arg+what,"class.php")
        app2_response[what+"model"]=what
        parsingoffile("base"+what+".class.php",arg+what+".class.php",classphp_response)
    else:
        print "you won't have a "+what+" system"

#we check if we are in the right directory
if (os.path.basename(os.getcwd())!="createapps"):
    print "Wrong path"
else:
    # check if an argument were given 
    if len(sys.argv) <2:
        print "Apps name needed as argument"
    else:
        arg = sys.argv[1]
        #check if the name is already use for the other apps
        if (os.path.exists("../"+arg)):
            print "Application already exists."
        else:
            # creation of all needed files and directories
            creationofdir("",arg)

            creationoffile("","config","xml")
            creationoffile("",arg,"app2")
            creationoffile("",arg,"class.php")

            creationofdir(arg,"languages")

            creationoffile("/languages","en","po")
            parsingoffile("baseen.po","languages/en.po","")
            creationoffile("/languages","fr","po")
            parsingoffile("basefr.po","languages/fr.po","")

            creationofdir(arg,"templates")

            creationoffile("/templates","mini","tpl")

            app2 = {"name_fr" : "french name of the app","name_en" : "english name of the app", "desc_fr" : "french description of the app", "desc_en" : "english description of the app" }
            app2_response = {}
            for (name, question) in app2.items():
                app2_response[name] = raw_input(question + ":")
                while ("\"" in app2_response[name]):
                    app2_response[name]=raw_input("please don't use \" in your name or description : ")

            app2_response["view"]="mini"

            classphp = {"name" : "What is your name", "mail" : "What is your e-mail"}
            classphp_response = {}
            for (name,question) in classphp.items():
                classphp_response[name] = raw_input(question + ":")

            classphp_response["ClassName"] = arg

            # all files needed are created, now we are going to create optional files
            prompt("config")
            prompt("submit")

            promptjs = raw_input("Do you want to use Javascript (Y/n)? ")
            if (promptjs in ["","yes","y","o","oui"]):
                print "you choose to use JavaScript"
                print creationoffile("/templates","js","tpl")
                app2_response["jsview"]="JS"
                js_response={}
                js_response["appname"]=arg
                print parsingoffile("basejs.tpl","templates/js.tpl",js_response)
            else:
                print "you won't have the JavaScript system"

            print parsingoffile("base.app2",arg+".app2",app2_response)
            cleaningoffile(arg+".app2",";")

            print parsingoffile("base.class.php",arg+".class.php",classphp_response)
