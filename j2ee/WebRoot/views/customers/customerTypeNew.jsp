<%@ page contentType="text/html; charset=UTF-8" pageEncoding="UTF-8" %>
<%@ page language="java" import="java.util.*"%>
<%@ taglib uri="http://struts.apache.org/tags-bean" prefix="bean"%> 
<%@ taglib uri="http://struts.apache.org/tags-html" prefix="html"%>
 
<%
String path = request.getContextPath();
String basePath = request.getScheme()+"://"+request.getServerName()+":"+request.getServerPort()+path+"/";
%>
<html:html>
	<head>
		<title>JSP for CustomerTypeForm form</title>
	<script type="text/javascript" src="<%=basePath %>res/scripts/global.js"></script>
	</head>
	<body>
		<html:form action="/customerType.do?action=save">
		<p><bean:message key="labels.customerTypeTitle"/></p>
		<hr/>
		<table>
			<tr>
				<td><bean:message key="labels.customerTypeCode"/></td>
				<td><html:text property="customerTypeCode"/></td>
			</tr>
			<tr>
				<td><bean:message key="labels.customerTypeName"/></td>
				<td><html:text property="customerTypeName"/></td>				
			</tr>
			
			<!-- <tr>
				<td><bean:message key="labels.customerTypeIsvoid"/></td>
				<td><html:radio property="isvoid" value="Y"/>禁用
					<html:radio property="isvoid" value="N" />启用</td>
			</tr>-->
			<tr>
				<td colspan="2">
					<html:errors property="customerTypeCode"/><br/>
					<html:errors property="customerTypeName"/><br/>
				</td>
			</tr>			
		</table>
	<hr/>
	<input type="button" value='<bean:message key="operation.label.list"/>' 
			onclick="Page_PostBack('<%=basePath %>customerType.do?action=pager')"/>

	<input type="button" 
		value='<bean:message key="operation.label.save" />' 
		onclick="Page_PostBack('<%=basePath %>customerType.do?action=save')"/>

	<input type="reset" value='<bean:message key="operation.label.reset"/>' />
		</html:form>
	</body>
</html:html>

