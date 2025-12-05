/*
----------------------------------
	SP: Eliminar Rol (Soft Delete)
----------------------------------
*/

CREATE OR ALTER PROCEDURE dbo.SP_EliminarRol
    @ID_Rol INT
AS
BEGIN
    SET NOCOUNT ON;

    DECLARE @Modulo VARCHAR(50) = 'Roles';
    DECLARE @CamposObligatorios VARCHAR(255) = '<ID_Rol>';

    BEGIN TRY
        IF @ID_Rol IS NULL
        BEGIN
            EXEC dbo.SP_ErrorEnCodigo
                @CodigoDeError = 50100,  
                @Modulo = @Modulo,
                @Detalles = @CamposObligatorios;
            RETURN;
        END

        IF NOT EXISTS (SELECT 1 FROM dbo.Roles WHERE ID_Rol = @ID_Rol)
        BEGIN
            EXEC dbo.SP_ErrorEnCodigo
                @CodigoDeError = 50500, 
                @Modulo = @Modulo,
                @Detalles = 'ID_Rol';
            RETURN;
        END

        UPDATE dbo.Roles SET Estatus = 0 WHERE ID_Rol = @ID_Rol AND Estatus <> 0;

        IF @@ROWCOUNT = 0
        BEGIN
            EXEC dbo.SP_ErrorEnCodigo 
                @CodigoDeError = 50400, 
                @Modulo = @Modulo;
        END
    END TRY

    BEGIN CATCH
        PRINT ERROR_MESSAGE();
        EXEC dbo.SP_ErrorEnCodigo
            @CodigoDeError = 50000,
            @Modulo = @Modulo;
    END CATCH
END
GO

/*
----------------------------------
	SP: Eliminar Usuario (Soft Delete)
----------------------------------
*/

CREATE OR ALTER PROCEDURE dbo.SP_EliminarUsuario
    @ID_Usuario INT
AS
BEGIN
    SET NOCOUNT ON;

    DECLARE @Modulo VARCHAR(50) = 'Usuarios';
    DECLARE @CamposObligatorios VARCHAR(255) = '<ID_Usuario>';

    BEGIN TRY
        IF @ID_Usuario IS NULL
        BEGIN
            EXEC dbo.SP_ErrorEnCodigo
                @CodigoDeError = 50100,  
                @Modulo = @Modulo,
                @Detalles = @CamposObligatorios;
            RETURN;
        END

        IF NOT EXISTS (SELECT 1 FROM dbo.Usuarios WHERE ID_Usuario = @ID_Usuario)
        BEGIN
            EXEC dbo.SP_ErrorEnCodigo
                @CodigoDeError = 50500, 
                @Modulo = @Modulo,
                @Detalles = 'ID_Usuario';
            RETURN;
        END

        UPDATE dbo.Usuarios SET Estatus = 0 WHERE ID_Usuario = @ID_Usuario AND Estatus <> 0;

        IF @@ROWCOUNT = 0
        BEGIN
            EXEC dbo.SP_ErrorEnCodigo 
                @CodigoDeError = 50400, 
                @Modulo = @Modulo;
        END
    END TRY

    BEGIN CATCH
        PRINT ERROR_MESSAGE();
        EXEC dbo.SP_ErrorEnCodigo
            @CodigoDeError = 50000,
            @Modulo = @Modulo;
    END CATCH
END
GO

/*
----------------------------------
	SP: Eliminar Tipo (Soft Delete)
----------------------------------
*/

CREATE OR ALTER PROCEDURE dbo.SP_EliminarTipo
    @ID_Tipo INT
AS
BEGIN
    SET NOCOUNT ON;

    DECLARE @Modulo VARCHAR(50) = 'Tipos';
    DECLARE @CamposObligatorios VARCHAR(255) = '<ID_Tipo>';

    BEGIN TRY
        IF @ID_Tipo IS NULL
        BEGIN
            EXEC dbo.SP_ErrorEnCodigo
                @CodigoDeError = 50100,  
                @Modulo = @Modulo,
                @Detalles = @CamposObligatorios;
            RETURN;
        END

        IF NOT EXISTS (SELECT 1 FROM dbo.Tipos WHERE ID_Tipo = @ID_Tipo)
        BEGIN
            EXEC dbo.SP_ErrorEnCodigo
                @CodigoDeError = 50500, 
                @Modulo = @Modulo,
                @Detalles = 'ID_Tipo';
            RETURN;
        END

        UPDATE dbo.Tipos SET Estatus = 0 WHERE ID_Tipo = @ID_Tipo AND Estatus <> 0;

        IF @@ROWCOUNT = 0
        BEGIN
            EXEC dbo.SP_ErrorEnCodigo 
                @CodigoDeError = 50400, 
                @Modulo = @Modulo;
        END
    END TRY

    BEGIN CATCH
        PRINT ERROR_MESSAGE();
        EXEC dbo.SP_ErrorEnCodigo
            @CodigoDeError = 50000,
            @Modulo = @Modulo;
    END CATCH
END
GO

/*
----------------------------------
	SP: Eliminar Producto (Soft Delete)
----------------------------------
*/

CREATE OR ALTER PROCEDURE dbo.SP_EliminarProducto
    @ID_Producto INT
AS
BEGIN
    SET NOCOUNT ON;

    DECLARE @Modulo VARCHAR(50) = 'Productos';
    DECLARE @CamposObligatorios VARCHAR(255) = '<ID_Producto>';

    BEGIN TRY
        IF @ID_Producto IS NULL
        BEGIN
            EXEC dbo.SP_ErrorEnCodigo
                @CodigoDeError = 50100,  
                @Modulo = @Modulo,
                @Detalles = @CamposObligatorios;
            RETURN;
        END

        IF NOT EXISTS (SELECT 1 FROM dbo.Productos WHERE ID_Producto = @ID_Producto)
        BEGIN
            EXEC dbo.SP_ErrorEnCodigo
                @CodigoDeError = 50500, 
                @Modulo = @Modulo,
                @Detalles = 'ID_Producto';
            RETURN;
        END

        UPDATE dbo.Productos SET Estatus = 0 WHERE ID_Producto = @ID_Producto AND Estatus <> 0;

        IF @@ROWCOUNT = 0
        BEGIN
            EXEC dbo.SP_ErrorEnCodigo 
                @CodigoDeError = 50400, 
                @Modulo = @Modulo;
        END
    END TRY

    BEGIN CATCH
        PRINT ERROR_MESSAGE();
        EXEC dbo.SP_ErrorEnCodigo
            @CodigoDeError = 50000,
            @Modulo = @Modulo;
    END CATCH
END
GO

/*
----------------------------------
	SP: Eliminar Venta (Soft Delete)
----------------------------------
*/

CREATE OR ALTER PROCEDURE dbo.SP_EliminarVenta
    @ID_Venta INT
AS
BEGIN
    SET NOCOUNT ON;

    DECLARE @Modulo VARCHAR(50) = 'Ventas';
    DECLARE @CamposObligatorios VARCHAR(255) = '<ID_Venta>';

    BEGIN TRY
        IF @ID_Venta IS NULL
        BEGIN
            EXEC dbo.SP_ErrorEnCodigo
                @CodigoDeError = 50100,
                @Modulo = @Modulo,
                @Detalles = @CamposObligatorios;
            RETURN;
        END

        IF NOT EXISTS (SELECT 1 FROM dbo.Ventas WHERE ID_Venta = @ID_Venta)
        BEGIN
            EXEC dbo.SP_ErrorEnCodigo
                @CodigoDeError = 50500,
                @Modulo = @Modulo,
                @Detalles = 'ID_Venta';
            RETURN;
        END

        UPDATE dbo.Ventas SET Estatus = 0 WHERE ID_Venta = @ID_Venta AND Estatus <> 0;

        IF @@ROWCOUNT = 0
        BEGIN
            EXEC dbo.SP_ErrorEnCodigo 
                @CodigoDeError = 50400, 
                @Modulo = @Modulo;
        END
    END TRY

    BEGIN CATCH
        PRINT ERROR_MESSAGE();
        EXEC dbo.SP_ErrorEnCodigo
            @CodigoDeError = 50000,
            @Modulo = @Modulo;
    END CATCH
END
GO