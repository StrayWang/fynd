<%@ page language="java" pageEncoding="UTF-8"%>
<%@ page language="java" import="java.util.*"%>
<%@ taglib uri="http://struts.apache.org/tags-bean" prefix="bean" %>
<%@ taglib uri="http://struts.apache.org/tags-html" prefix="html" %>
<%@ taglib uri="http://struts.apache.org/tags-logic" prefix="logic" %>
<%@ taglib uri="http://struts.apache.org/tags-tiles" prefix="tiles" %>

<%
String path = request.getContextPath();
String basePath = request.getScheme()+"://"+request.getServerName()+":"+request.getServerPort()+path+"/";
%>

<html:html lang="true">
  <head>
    <html:base />
    
    <title>customerTypeView.jsp</title>

	<meta http-equiv="pragma" content="no-cache">
	<meta http-equiv="cache-control" content="no-cache">
	<meta http-equiv="expires" content="0">    
	<meta http-equiv="keywords" content="keyword1,keyword2,keyword3">
	<meta http-equiv="description" content="This is my page">
	<link rel="Stylesheet" type="text/css" href="<%=basePath %>res/css/global_components.css" />
	<script type="text/javascript" src="<%=basePath %>res/scripts/global.js"></script>
  </head>

  <body>
	
    <html:form action="/customerTypeView.do?action=view" method="get">
		<h2 class="title"><bean:message key="labels.customerTypeTitle"/></h2>
		<div style="width:100%;text-align:center">
	<input type="button" value='<bean:message key="operation.label.list"/>' 
			onclick="Page_PostBack('<%=basePath %>customerType.do?action=pager')"/>

	<input type="button" value='<bean:message key="operation.label.delete"/>' 
			onclick="Page_PostBack('<%=basePath %>customerType.do?action=delete&customerTypeId=<%= request.getParameter("customerTypeId")%>')"/>
	
	<input type="button" value='<bean:message key="operation.label.edit"/>' 
			onclick="Page_PostBack('<%=basePath %>customerType.do?action=edit&customerTypeId=<%= request.getParameter("customerTypeId")%>')"/>
	</div>

		<hr/>
      <table border="0">
		<tr>
          <td align="left"><bean:message key="labels.customerTypeCode"/>:</td>
          <td><span style="text-decoration:underline">
          	<bean:write name="customerTypeForm" property="customerTypeCode"/></span></td>
        </tr>
        <tr>
          <td align="left"><bean:message key="labels.customerTypeName"/>:</td>
          <td><span style="text-decoration:underline">
          	<bean:write name="customerTypeForm" property="customerTypeName"/></span></td>
        </tr>
      </table>
		<hr/>
	
	<div style="width:100%;text-align:center">
	<input type="button" value='<bean:message key="operation.label.list"/>' 
			onclick="Page_PostBack('<%=basePath %>customerType.do?action=pager')"/>

	<input type="button" value='<bean:message key="operation.label.delete"/>' 
			onclick="Page_PostBack('<%=basePath %>customerType.do?action=delete&customerTypeId=<%= request.getParameter("customerTypeId")%>')"/>
	
	<input type="button" value='<bean:message key="operation.label.edit"/>' 
			onclick="Page_PostBack('<%=basePath %>customerType.do?action=edit&customerTypeId=<%= request.getParameter("customerTypeId")%>')"/>
	</div>
    </html:form>
</body>
</html:html>
