
/*
--------------------------------------------------
	SP: Insertar Dirección (sin Estatus)
--------------------------------------------------
*/
CREATE OR ALTER PROCEDURE dbo.SP_InsersionDeDireccion
	@Pais NVARCHAR(100),
	@Estado NVARCHAR(100),
	@Municipio NVARCHAR(100),
	@Localidad_comunidad NVARCHAR(100),
	@Calle NVARCHAR(100),
	@No_exterior VARCHAR(10) = NULL,
	@No_interior VARCHAR(10) = NULL,
	@Colonia NVARCHAR(100) = NULL,
	@Tipo_asentamiento NVARCHAR(50) = NULL,
	@Tipo_vialidad NVARCHAR(50) = NULL,
	@Codigo_postal CHAR(5),
	@ID_Direccion INT OUTPUT
AS
BEGIN
	SET NOCOUNT ON;

	DECLARE @Modulo VARCHAR(50) = 'Direcciones';
	DECLARE @CamposObligatorios VARCHAR(255) = '<Pais>, <Estado>, <Municipio>, <Calle>, <Codigo_postal>';

	BEGIN TRY
		IF @Pais IS NULL OR LTRIM(RTRIM(@Pais)) = ''
		   OR @Estado IS NULL OR LTRIM(RTRIM(@Estado)) = ''
		   OR @Municipio IS NULL OR LTRIM(RTRIM(@Municipio)) = ''
		   OR @Calle IS NULL OR LTRIM(RTRIM(@Calle)) = ''
		   OR @Codigo_postal IS NULL OR LTRIM(RTRIM(@Codigo_postal)) = ''
		BEGIN
			EXEC dbo.SP_ErrorEnCodigo 
				@CodigoDeError = 50100, 
				@Modulo = @Modulo, 
				@Detalles = @CamposObligatorios;
			RETURN;
		END;

		SET @Localidad_comunidad = ISNULL(NULLIF(LTRIM(RTRIM(@Localidad_comunidad)), ''), 'N/D');
		SET @No_exterior = ISNULL(NULLIF(LTRIM(RTRIM(@No_exterior)), ''), 'S/N');
		SET	@No_interior = ISNULL(NULLIF(LTRIM(RTRIM(@No_interior)), ''), 'S/N');
		SET	@Colonia = ISNULL(NULLIF(LTRIM(RTRIM(@Colonia)), ''), 'N/D');
		SET	@Tipo_asentamiento = ISNULL(NULLIF(LTRIM(RTRIM(@Tipo_asentamiento)), ''), 'N/A');
		SET	@Tipo_vialidad = ISNULL(NULLIF(LTRIM(RTRIM(@Tipo_vialidad)), ''), 'N/A');

		SELECT @ID_Direccion = ID_Direccion
		FROM dbo.Direcciones
		WHERE Pais = @Pais
		  AND Estado = @Estado
		  AND Municipio = @Municipio
		  AND Localidad_comunidad = @Localidad_comunidad
		  AND Calle = @Calle
		  AND No_exterior = @No_exterior
		  AND No_interior = @No_interior
		  AND Colonia = @Colonia
		  AND Tipo_asentamiento = @Tipo_asentamiento
		  AND Tipo_vialidad = @Tipo_vialidad
		  AND Codigo_postal = @Codigo_postal;

		IF @ID_Direccion IS NULL
		BEGIN
			INSERT INTO dbo.Direcciones
				(Pais, Estado, Municipio, Localidad_comunidad, Calle, No_exterior, No_interior, Colonia, Tipo_asentamiento, Tipo_vialidad, Codigo_postal)
			VALUES
				(@Pais, @Estado, @Municipio, @Localidad_comunidad, @Calle, @No_exterior, @No_interior, @Colonia, @Tipo_asentamiento, @Tipo_vialidad, @Codigo_postal);

			SET @ID_Direccion = SCOPE_IDENTITY();
		END;
	END TRY
	BEGIN CATCH
		PRINT ERROR_MESSAGE();
		EXEC dbo.SP_ErrorEnCodigo 
			@CodigoDeError = 50000, 
			@Modulo = @Modulo; 
	END CATCH;
END;
GO



/*
----------------------------------
	SP: Insertar Rol
----------------------------------
*/
CREATE OR ALTER PROCEDURE dbo.SP_AgregarRol
    @Nombre VARCHAR(100),
    @Estatus INT = NULL,
    @ID_Rol INT OUTPUT
AS
BEGIN
    SET NOCOUNT ON;
    DECLARE @Modulo VARCHAR(50) = 'Roles';
    DECLARE @CamposObligatorios VARCHAR(255) = '<Nombre>';

    BEGIN TRY
        SET @Estatus = ISNULL(@Estatus, 1);

        IF @Nombre IS NULL OR LTRIM(RTRIM(@Nombre)) = ''
        BEGIN
            EXEC dbo.SP_ErrorEnCodigo @CodigoDeError = 50100, @Modulo = @Modulo, @Detalles = @CamposObligatorios;
            RETURN;
        END;

        IF EXISTS (SELECT 1 FROM dbo.Roles WHERE Nombre = @Nombre)
        BEGIN
            EXEC dbo.SP_ErrorEnCodigo @CodigoDeError = 50200, @Modulo = @Modulo, @Detalles = 'El nombre del rol ya existe.';
            RETURN;
        END;

        INSERT INTO dbo.Roles (Nombre, Estatus)
        VALUES (@Nombre, @Estatus);

        SET @ID_Rol = SCOPE_IDENTITY();
    END TRY
    BEGIN CATCH
        PRINT ERROR_MESSAGE();
        EXEC dbo.SP_ErrorEnCodigo 
			@CodigoDeError = 50000, 
			@Modulo = @Modulo; 
    END CATCH;
END;
GO


/*
----------------------------------
	SP: Insertar Venta
----------------------------------
*/
CREATE OR ALTER PROCEDURE dbo.SP_AgregarVenta
    @ID_Usuario INT,
    @ID_Producto INT,
    @ID_Cantidad INT,
    @Estatus INT = NULL,
    @ID_Venta INT OUTPUT
AS
BEGIN
    SET NOCOUNT ON;
    DECLARE @Modulo VARCHAR(50) = 'Ventas';
    DECLARE @CamposObligatorios VARCHAR(255) = '<ID_Usuario>, <ID_Producto>, <ID_Cantidad>';

    BEGIN TRY
        SET @Estatus = ISNULL(@Estatus, 1);

        IF @ID_Usuario IS NULL OR @ID_Producto IS NULL OR @ID_Cantidad IS NULL
        BEGIN
            EXEC dbo.SP_ErrorEnCodigo @CodigoDeError = 50100, @Modulo = @Modulo, @Detalles = @CamposObligatorios;
            RETURN;
        END;

        IF NOT EXISTS (SELECT 1 FROM dbo.Usuarios WHERE ID_Usuario = @ID_Usuario)
        BEGIN
            EXEC dbo.SP_ErrorEnCodigo @CodigoDeError = 50500, @Modulo = @Modulo, @Detalles = 'ID_Usuario';
            RETURN;
        END;

        IF NOT EXISTS (SELECT 1 FROM dbo.Productos WHERE ID_Producto = @ID_Producto)
        BEGIN
            EXEC dbo.SP_ErrorEnCodigo @CodigoDeError = 50500, @Modulo = @Modulo, @Detalles = 'ID_Producto';
            RETURN;
        END;

        IF NOT EXISTS (SELECT 1 FROM dbo.Cantidades WHERE ID_Cantidad = @ID_Cantidad)
        BEGIN
            EXEC dbo.SP_ErrorEnCodigo @CodigoDeError = 50500, @Modulo = @Modulo, @Detalles = 'ID_Cantidad';
            RETURN;
        END;

        INSERT INTO dbo.Ventas (ID_Usuario, ID_Producto, ID_Cantidad, Estatus)
        VALUES (@ID_Usuario, @ID_Producto, @ID_Cantidad, @Estatus);

        SET @ID_Venta = SCOPE_IDENTITY();
    END TRY
    BEGIN CATCH
        PRINT ERROR_MESSAGE();
        EXEC dbo.SP_ErrorEnCodigo 
			@CodigoDeError = 50000, 
			@Modulo = @Modulo; 
    END CATCH;
END;
GO


/*
----------------------------------
	SP: Insertar Cantidad
----------------------------------
*/
CREATE OR ALTER PROCEDURE dbo.SP_AgregarCantidad
    @Cantidad INT,
    @Descripcion VARCHAR(50) = NULL,
    @Estatus INT = NULL,
    @ID_Cantidad INT OUTPUT
AS
BEGIN
    SET NOCOUNT ON;

    DECLARE @Modulo VARCHAR(50) = 'Cantidades';
    DECLARE @CamposObligatorios VARCHAR(255) = '<Cantidad>';

    BEGIN TRY
        SET @Estatus = ISNULL(@Estatus, 1);

        IF @Cantidad IS NULL OR @Cantidad <= 0
        BEGIN
            EXEC dbo.SP_ErrorEnCodigo 
                @CodigoDeError = 50100, 
                @Modulo = @Modulo, 
                @Detalles = @CamposObligatorios;
            RETURN;
        END;

        IF EXISTS (SELECT 1 FROM dbo.Cantidades WHERE Cantidad = @Cantidad)
        BEGIN
            EXEC dbo.SP_ErrorEnCodigo 
                @CodigoDeError = 50200, 
                @Modulo = @Modulo, 
                @Detalles = 'La cantidad ya está registrada.';
            RETURN;
        END;

        INSERT INTO dbo.Cantidades (Cantidad, Descripcion, Estatus)
        VALUES (@Cantidad, @Descripcion, @Estatus);

        SET @ID_Cantidad = SCOPE_IDENTITY();
    END TRY
    BEGIN CATCH
        PRINT ERROR_MESSAGE();
        EXEC dbo.SP_ErrorEnCodigo 
			@CodigoDeError = 50000, 
			@Modulo = @Modulo; 
    END CATCH;
END;
GO
