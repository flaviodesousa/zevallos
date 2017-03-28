# $Id: Makefile 40 2007-02-22 14:45:04Z eduardo $

JAVAC=javac
JFLAGS=-source 1.4 -target 1.4
.SUFFIXES: .java .class

.java.class:
	$(JAVAC) $(JFLAGS) $*.java

all: JIsAvail.class jar javadoc

clean:
	-find ./ -name "*.class" -delete
	-find ./ -name "*~" -delete
	-rm cookie

distclean: clean
	-rm *.jar
	-rm -rf javadoc

jar: JIsAvail.jar

JIsAvail.jar: JIsAvail.class \
	br/nic/isavail/AvailClient.class \
	br/nic/isavail/AvailResponseParser.class \
	br/nic/isavail/IsAvailDefs.class
	jar cmf mainClass JIsAvail.jar *.class br/nic/isavail/*.class

javadoc: br/nic/isavail/AvailClient.java \
	br/nic/isavail/AvailResponseParser.java \
	br/nic/isavail/IsAvailDefs.java
	javadoc br.nic.isavail -d javadoc -link http://java.sun.com/j2se/1.4.2/docs/api/
