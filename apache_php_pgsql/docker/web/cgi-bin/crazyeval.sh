#!/bin/bash

# this function bash only
_urldecode() { : "${*//+/ }"; echo -e "${_//%/\\x}"; }

_dangeroustest() {
	local TITLE DECODEDVAL EVALRESULT EVALERR BADRESULT;
	
	[ $# -eq 2 ]  || {
		echo "Usage: _dangeroustest title param"
		exit 1
	}
	TITLE=$1; shift
	DECODEDVAL=$1;
	[ "$DECODEDVAL" ] || {
		echo "${TITLE} is blank.";
		return;
	}
	eval BADRESULT=$DECODEDVAL;
	EVALERR=$?
	if [ $EVALERR -ne 0 ]; then
		echo "${TITLE}|eval error status: $EVALERR"
	else    
		echo "${TITLE}|eval command \"${DECODEDVAL}\" was executed.";
	fi
}

_dangerousParseQueries() {
	local IFS='&' _queries="$QUERY_STRING" _param _name _value _title=$1 EVALERR
	for _param in $_queries; do
		_name=$(_urldecode ${_param%=*})
		eval _nameResult=$_name
		EVALERR=$?
		if [ $EVALERR -ne 0 ]; then
			echo "${_title}|eval name error status: $EVALERR"
		elif [ "${_nameResult}" ]; then
			echo ${_title}"|eval name[${_name}]->[${_nameResult}]"
		else
			echo ${_title}"|eval name[${_name}]  command was executed."
		fi
		
		_value=$(_urldecode ${_param#*=})
		if [ "$_value" ]; then
			eval _valueResult=$_value
			EVALERR=$?
			if [ $EVALERR -ne 0 ]; then
				echo "${_title}|eval value error status: $EVALERR"
			elif [ "${_valueResult}" ]; then   
				echo ${_title}"|eval value[${_value}]->[${_valueResult}]"
			else
				echo ${_title}"|eval value[${_value}] command was executed."
			fi
			
		fi
	done
}

_safetest() {
	local DECODEDVAL=$* SAFERESULT NUM EXPRERR;
	eval SAFERESULT=\$DECODEDVAL;
	echo "SAFE eval RESULT:"${SAFERESULT};
	NUM=$(expr $DECODEDVAL)
	EXPRERR=$?
	if [ $EXPRERR -ne 0 ]; then
		echo "expr error status: $EXPRERR"
	else
		echo "SAFE expr RESULT:"${NUM};
	fi
}

printf "Content-Type: text/plain\r\n"
printf "\r\n"
echo "WARNING: In this script, functions with names prefixed with _dangerous can execute any command you specify."
echo "        This could potentially damage your Docker containers or your hosts connected to your network."
echo "        Do not expose this cgi to other networks or hosts you do not recognized. "
echo ""
RAW=$*
DECODEDVAL=$(_urldecode "$*")
echo "SHELL ARGS RAW:"${RAW}
echo "SHELL ARGS DECODED:"${DECODEDVAL}
### these below function is test for executing commands by eval. so this is dangerous.
#_dangeroustest "SHELL ARGS" "$DECODEDVAL"
#_dangerousParseQueries "QUERY_STRING"
### this below function is safe.
_safetest "$DECODEDVAL"
