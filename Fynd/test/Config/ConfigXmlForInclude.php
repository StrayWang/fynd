<?php
return <<<XML
<?xml version="1.0" encoding="utf-8"?>
<FyndConfig>
	<DbConfig>
		<DefaultConnection>主机1</DefaultConnection>
		<Connections>
			<Connection Name="主机1" Type="MySQL">
				<Server>127.0.0.1</Server>
				<Port>3306</Port>
				<DataBase>simplespace</DataBase>
				<User>test</User>
				<Password>123456</Password>
			</Connection>
			<Connection Name="主机2" Type="MySQL">
				<Server>127.0.0.1</Server>
				<Port>3306</Port>
				<DataBase>test</DataBase>
				<User>root</User>
				<Password>windfir</Password>
			</Connection>
			<Connection Name="主机3" Type="MSSQL">
				<Server>localhost</Server>
				<Port>1433</Port>
				<DataBase>test</DataBase>
				<User>sa</User>
				<Password>123456</Password>
			</Connection>
			<Connection Name="主机4" Type="Oracle">
				<Server>dev.cn</Server>
				<Port>1521</Port>
				<SID>TEST</SID>
				<User>MY_TEST</User>
				<Password>123456</Password>
			</Connection>
			<Connection Name="主机5" Type="SQLite">
				<DataBaseFilePath>/path/to/sqlite/database/file</DataBaseFilePath>
			</Connection>
		</Connections>
	</DbConfig>
</FyndConfig>            
XML;
?>