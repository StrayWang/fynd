Fynd.UI.Picker = function(id,config)
{
    this.selectedId = (config.selectedId) ? selectedId : [];
    this.tableId = config.tableId;
    this.tableIdKey = config.tableIdKey;
    this.tableLabelKey = config.tableLabelKey;
    this.multiSelection = config.multiSelection;
    this.href = config.href;
    this.id = id;
    this.yuiDialog = null;
    this.dialogTable = null;
    this.dialogTree = null;
    this.dialogTableDataSource = null;
    this.tableElId = this.id + '-table';
    this.treeElId = this.id + '-tree';
    
    this.selectedTreeNodeDef = null;
};
Fynd.UI.Picker.prototype = 
{
    initSelectedId : function()
    {
        var tableObj = Fynd.UI.ComponentStack.find(this.tableId);
        if(tableObj)
        {
            var rs = tableObj.getRecordSet();
            for(var i = 0;i<rs.getLength();i++)
            {
                var record = rs.getRecord(i);
                this.selectedId[i][this.tableIdKey] = record.getData(this.tableIdKey);
                this.selectedId[i][this.tableLabelKey] = record.getData(this.tableLabelKey);
            }
        }
    },
    init : function()
    {
        this.initSelectedId();
        
        var pickerEl = document.getElementById(this.id);
        var divTable = document.createElement("div");
        pickerEl.appendChild(divTable);
        divTable.id = this.tableElId;
        //TODO:build search bar
        
        var divTree = document.createElement('div');
        pickerEl.appendChild(divTree);
        divTree.id = this.treeElId;
        
        var divButton = document.createElement('div');
        pickerEl.appendChild(divButton);
        divButton.id = this.id + '-button';
        
        var successHandler = function(p)
        {
            var picker = p.argument.object;
            var responseObj = YAHOO.lang.JSON.parse(p.responseText);
            
            //begin table creation
            //create table column definition
            var dialogTableColumDef = responseObj.table.columnDefinition;
            if(picker.multiSelection)
            {
                dialogTableColumDef[0].formatter = function(elCell, oRecord, oColumn, oData)
                {
                    var inputType = 'checkbox';
                    elCell.innerHTML = '<input type="' + inputType + '"' +
                        ' name="' + oColumn.getKey() + '-' + inputType + '" />';
                };
            }
            else
            {
                dialogTableColumDef[0].formatter = function(elCell, oRecord, oColumn, oData)
                {
                    var inputType = 'radio';
                    elCell.innerHTML = '<input type="' + inputType + '"' +
                        ' name="' + oColumn.getKey() + '-' + inputType + '" />';
                };
            }
            
            //create table configures.
            var dialogTableConfig = {
                initialRequest  : "&fetch=table&startIndex=0&results=10",
                dynamicData     : true,
                paginator       : new YAHOO.widget.Paginator({ rowsPerPage:10 }),
                generateRequest : function(oState, oSelf){
                    oState = oState || { pagination:null, sortedBy:null };
                    var sort = (oState.sortedBy) ? oState.sortedBy.key : '';
                    var dir = (oState.sortedBy && oState.sortedBy.dir === YAHOO.widget.DataTable.CLASS_DESC) ? "desc" : "asc";
                    var startIndex = (oState.pagination) ? oState.pagination.recordOffset : 0;
                    var results = (oState.pagination) ? oState.pagination.rowsPerPage : null;
                    return  "fetch=table&" + 
                        ((picker.selectedTreeNodeDef) ? 'query=' + picker.selectedTreeNodeDef.value+'&' : '') +
                        ((sort == '') ? '' : "sort=" + sort + "&dir=" + dir + "&") +
                            "startIndex=" + startIndex +
                            ((results !== null) ? "&results=" + results : "");
                }
            };
            //create table data source.
            picker.dialogTableDataSource = new YAHOO.util.DataSource(picker.href + "fetch=table&");
            picker.dialogTableDataSource.responseType = YAHOO.util.DataSource.TYPE_JSON;
            picker.dialogTableDataSource.responseSchema = {
                resultsList : "records",
                metaFields  : {
                    totalRecords : "totalRecords"
                }
            };
            picker.dialogTable = new YAHOO.widget.DataTable(picker.id, dialogTableColumDef, picker.dialogTableDataSource, dialogTableConfig);
            
            //subscribe the checkbox or radio's click event to add the current selected value to array of selected value .
            if(picker.multiSelection)
            {
                picker.dialogTable.subscribe('checkboxClickEvent', function(e)
                {
                    var scope = e.object;
                    var checked = e.target.checked;
                    var record = this.getRecord(e.target);
                    var value = record.getData(scope.tableIdKey);
                    var label = record.getData(scope.tableLabelKey);
                    var existent = false;
                    if(checked)
                    {
                        for(var i=0;i<scope.selectedId.length;i++)
                        {
                            if(value == scope.selectedId[i][scope.tabelIdKey])
                            {
                                existent = true;
                                break;
                            }
                        }
                        if(!existent)
                        {
                            var item = {};
                            item[scope.tableIdKey] = value;
                            item[scope.tableLabelKey] = label;
                            scope.selectedId.push(item);
                        }
                    }
                },picker);
            }
            else
            {
                picker.dialogTable.subscribe('radioClickEvent', function(e)
                {
                    var picker = e.object;
                    var checked = e.target.checked;
                    var record = this.getRecord(e.target);
                    var value = record.getData(scope.tableIdKey);
                    var label = record.getData(scope.tableLabelKey);
                    var existent = false;
                    if(checked)
                    {
                        var item = {};
                        item[scope.tableIdKey] = value;
                        item[scope.tableLabelKey] = label;
                        scope.selectedId[0] = item;
                    }
                },picker);
            }
            //end table creation
            
            var dialogTreeConfig = responseObj.tree.config;
            var dialogTreeData = responseObj.tree.data;
            
            picker.dialogTree = new YAHOO.widget.TreeView(picker.treeElId);
            picker.initTreeNode(picker.dialogTree.getRoot(),dialogTreeData);
            
            var treeNodeClickHandler = function(e,node)
            {
                var nodeDef = node.getNodeDefinition();
                var url = 'fetch=table&startIndex=0&results=10&query=' + nodeDef.value;
                this.dialogTableDataSource.sendRequest(url, this.dialogTableDataSource.onDataReturnInitializeTable, this.dialogTable); 
            };
            yuiTree.subscribe("clickEvent",treeNodeClickHandler,picker,picker);
            
            var btnOk =  { 
                    text      : "确定", 
                    handler   : {fn : this.handleDialogOk, object : null, scope : picker},
                    isDefault : true 
            };
            var btnCancel = {
                    text:"取消", 
                    handler:{fn : this.handleDialogCancel, object : null, scope : picker}
            };
            var dialogConfig =
            {
                width               : "800px",
                fixedcenter         : true,
                visible             : false, 
                constraintoviewport : true,
                buttons             : [btnOk,btnCancel]
            }; 
            picker.yuiDialog = new YAHOO.widget.Dialog(picker.id, dialogConfig);
            picker.yuiDialog.render();
        };
        var responseHandler = function handleResponse(p)
        {
            alert(p.responseText);
        };
        var callback = 
        {
            success  : responseHandler,
            failure  : responseHandler,
            argument : {object : this}
        }
        YAHOO.util.Connect.asyncRequest("GET", this.href, callback);
    },
    
    initTreeNode : function(parentNode,data)
    {
        for(var i=0; i<data.length;i++)
        {
            var node = new YAHOO.widget.TextNode(data[i],parentNode,false);
            if(YAHOO.lang.isArray(data[i].children))
            {
                this.initTreeNode(node,data[i].children);
            }
        }
    },
    handleDialogOk : function(e)
    {
        var tableObj = Fynd.UI.ComponentStack.find(this.tableId);
        if(tableObj)
        {
            var rs = tableObj.getRecordSet();
            for(var j = 0;j<this.selectedId.length;j++)
            {
                var existent = false;
                for(var i = 0;i<rs.getLength();i++)
                {
                    var existValue = rs.getRecord(i).getData(this.tableIdKey);
                    if(existValue == this.selectedId[i][this.tableIdKey])
                    {
                        existent = true;
                        break;
                    }
                }
                if(!existent)
                {
                    tableObj.addRow(this.selectedId[i]);
                }
            }
        }
        this.yuiDialog.hide();
    },
    handleDialogCancel : function(e)
    {
        this.yuiDialog.hide();
    },
    open : function()
    {
        if(this.yuiDialog)
        {
            this.initSelectedId();
            this.yuiDialog.show();
        }
        else
        {
            this.init();
        }
    },
    close : function()
    {
        this.yuiDialog.hide();
    }
};