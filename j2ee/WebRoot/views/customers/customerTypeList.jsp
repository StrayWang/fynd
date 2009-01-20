<%@ page language="java" pageEncoding="UTF-8"%>
<%@ page language="java" import="java.util.*"%>
<%@ taglib uri="http://struts.apache.org/tags-bean" prefix="bean"%> 
<%@ taglib uri="http://struts.apache.org/tags-html" prefix="html"%>
<%@ taglib uri="http://struts.apache.org/tags-logic" prefix="logic" %>
<%@ taglib uri="http://struts.apache.org/tags-tiles" prefix="tiles" %>
 
<%
String path = request.getContextPath();
String basePath = request.getScheme()+"://"+request.getServerName()+":"+request.getServerPort()+path+"/";
%>
<html> 
	<head>
		<title>JSP for CustomerTypeListForm form</title>
	<link rel="Stylesheet" type="text/css" href="<%=basePath %>res/css/global_components.css" />
	<script type="text/javascript" src="<%=basePath %>res/scripts/global.js"></script>
	</head>

	<body>
	<h2 class="title"><bean:message key="labels.customertypelisttitle"/>——样例</h2>

	<div style="width:100%;text-align:center">
	<input type="button" value='<bean:message key="operation.label.new" />' onclick="Page_PostBack('<%=basePath %>views/customers/customerTypeEdit.jsp',false)"/>
	<input type="button" value='<bean:message key="operation.label.delete" />' onclick="Page_PostBack('<%=basePath %>customerType.do?action=deleteBatch',true)"/>
	</div>

	<hr />
		<html:form>
	<div style="width:100%;height:100%;text-align:center;">
		<table cellspacing="0" cellpadding="0" style="width:70%;font-size:11pt">
		<tr>
			<th align="center" style="width:80px">
				<!--<bean:message key="labels.selectall" />-->
				<input id="selectAll" type="checkbox" title='全选' onclick="List_SelectAll('customerTypeIds',this)">
			</th>
			<th align="left"><bean:message key="labels.customerTypeCode" /></th>
			<th align="left"><bean:message key="labels.customerTypeName" /></th>
			<th align="center">操作</th>
		</tr>
		<!-- 显示列表数据 -->
		<%
			int j = 0;
			String rowStyle = "";
		%>
		<logic:present name="customerTypeForm" scope="request" property="customerTypes">
			<logic:iterate id="customerTypeList" name="customerTypeForm" offset="0" scope="request" property="customerTypes">
			<%
				if (j%2==0){
					rowStyle = "bgcolor='#FFFFFF'";
				} else {
					rowStyle = "bgcolor='#F4F4F4'";
				}
			%>					
				<tr <%=rowStyle %> onmouseover="List_MouseOverRowStyle(this)" onmouseout="List_MouseOutRowStyle(this)">
					<td align="center">
						<input type="checkbox" name="customerTypeIds" value='<bean:write name="customerTypeList" property="customertypeid" />'>
					</td>			
					<td align="left">
						<a href="<%=basePath %>customerType.do?action=view&customerTypeId=<bean:write name="customerTypeList" property="customertypeid" />">
							<bean:write name="customerTypeList" property="customertypecode" /></a>
					</td>
					<td align="left"><bean:write name="customerTypeList" property="customertypename" /></td>
					<td align="center">
						<a href="<%=basePath %>customerType.do?action=edit&customerTypeId=<bean:write name="customerTypeList" property="customertypeid" />">
							<bean:message key="operation.label.edit" /></a>&nbsp;
						<a href="<%=basePath %>customerType.do?action=view&customerTypeId=<bean:write name="customerTypeList" property="customertypeid" />">
							浏览</a>
					</td>
				</tr>
			<%
				j++;
			%>
			</logic:iterate>
		</logic:present>
		<tfoot>
			<tr>
				<td colspan="4" align="right" class="pager">
					<a href='javascript:List_PrevPage("<%=basePath %>customerType.do?action=pager", <bean:write name="customerTypeForm" property="currentPage"/>)'>上一页</a>
					<a href='javascript:List_NextPage("<%=basePath %>customerType.do?action=pager", <bean:write name="customerTypeForm" property="currentPage"/>)'>下一页</a>
					第<input style="text-align:center" type="text" value='<bean:write name="customerTypeForm" property="currentPage"/>' onchange="Page_PostBack('<%=basePath %>customerType.do?action=pager&page='+this.value,true)" style="width:30px"/>页/共<bean:write name="customerTypeForm" property="totalPages" />页</td>
			</tr>
		</tfoot>
		<logic:notPresent name="customerTypeForm" scope="request" property="customerTypes">
			<bean:message key="labels.customertypeempty"/>
		</logic:notPresent>
		</table>
	<br/>
	<hr />
	
	<input type="button" value='<bean:message key="operation.label.new" />' onclick="Page_PostBack('<%=basePath %>views/customers/customerTypeEdit.jsp',false)"/>
	<input type="button" value='<bean:message key="operation.label.delete" />' onclick="Page_PostBack('<%=basePath %>customerType.do?action=deleteBatch',true)"/>
	
	</html:form>
</div>
	</body>
</html>

