
/*
============================
   Plantillas de la Pagina
============================
*/

--Direcciones 
If not exists (Select 1 From sys.tables where name = 'Direcciones' and schema_id = schema_id('dbo'))
Begin
	Create table dbo.Direcciones(
	ID_Direccion Int Identity(1,1) Primary Key,
	Pais Nvarchar (100) not null,
	Estado Nvarchar (100) not null,
	Municipio Nvarchar (100) not null,
	Localidad_comunidad Nvarchar (100) not null default '',
	Calle Nvarchar (100) not null,
	No_exterior Varchar (10) not null default 'S/N',
	No_interior Varchar (100) not null default 'N/D',
	Colonia NVARCHAR(100) NOT NULL DEFAULT 'N/D',
	Tipo_asentamiento Nvarchar (50) not null default 'N/A',
	Tipo_vialidad Nvarchar (50) not null default 'N/A',
	Codigo_postal Char (5) not null
	);
END