<%@ page language="java" pageEncoding="GBK"%>
<%@ taglib uri="http://struts.apache.org/tags-bean" prefix="bean"%> 
<%@ taglib uri="http://struts.apache.org/tags-html" prefix="html"%>
 
<html> 
	<head>
		<title>JSP for CustomerForm form</title>
	</head>
	<body>
		<html:form action="/customer">
			customerName : <html:text property="customerName"/><html:errors property="customerName"/><br/>
			sex : <html:radio property="sex" value="1"/>ÄÐ 
				<html:radio property="sex" value="0"/>Å® <html:errors property="sex"/><br/>
			customerTypeId : <html:select property="customerTypeId"/><html:errors property="customerTypeId"/><br/>
			<html:submit/><html:cancel/>
		</html:form>
	</body>
</html>

