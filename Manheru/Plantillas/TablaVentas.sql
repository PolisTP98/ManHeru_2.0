
/*
	Plantilla Tabla Ventas
*/

IF NOT EXISTS (SELECT 1 FROM sys.tables WHERE name = 'Ventas' AND schema_id = SCHEMA_ID('dbo'))
BEGIN
    CREATE TABLE dbo.Ventas(
	ID_Venta Int IDENTITY(1,1) PRIMARY KEY,
	ID_Usuario Int not null,
	ID_Producto Int not null,
	ID_Cantidad Int not null, 
	Estatus Bit,
	Foreign Key (ID_Usuario) References Usuarios (ID_Usuario),
	Foreign Key (ID_Cantidad) References Cantidades (ID_Cantidad),
	Foreign Key (ID_Producto) References Productos (ID_Producto)
    );
END
GO