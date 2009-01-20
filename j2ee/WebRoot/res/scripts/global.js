/**
 * 页面提交及ACTION转向
 * */
function Page_PostBack(target,isaction)
{
	var theForm = document.forms[0];
	
	if (theForm !== null)
	{
		if (isaction === true || isaction === null || typeof(isaction) == 'undefined')
		{				
			theForm.action = target;
			theForm.method = "POST";
			theForm.submit();
		}
		else
		{
			window.location=target;
		}
	}
}

function List_SelectAll(name,checkboxObj)
{
	var objs = document.getElementsByName(name);
	
	for (var i = 0; i < objs.length; i++)
	{
		objs[i].checked = checkboxObj.checked;
	}
}

function List_MouseOverRowStyle(rowObj)
{
	if (rowObj.style !== null)
	{
		rowObj.className = "listRow_MouseOver";		
	}
}

function List_MouseOutRowStyle(rowObj)
{
	if (rowObj.style !== null)
	{
		rowObj.className = "listRow_MouseOut";		
	}
} 

/**
 * 列表页号变化时的处理函数
 * */
function List_PageIndexChangedEventHandler(target, currentPage)
{
	var pagedActionTarget = target+"&page="+currentPage;
	
	Page_PostBack(pagedActionTarget, true);
}

/**
 * 前一页
 * */
function List_PrevPage(target, currPage){	
	List_PageIndexChangedEventHandler(target, --currPage);
}

/**
 * 后一页
 * */
function List_NextPage(target, currPage)
{
	List_PageIndexChangedEventHandler(target, ++currPage);
}
