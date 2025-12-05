CREATE OR ALTER PROCEDURE dbo.SP_ErrorEnCodigo
    @CodigoDeError INT,
	@Modulo VARCHAR(50),
	@Detalles VARCHAR(255) = NULL
AS
BEGIN
    SET NOCOUNT ON;

	BEGIN TRY
		IF @CodigoDeError IS NULL OR @Modulo IS NULL OR LTRIM(RTRIM(@Modulo)) = ''
		BEGIN
			THROW 50600, '[Errores] Los siguientes campos no aceptan valores NULL: <CodigoDeError>, <Modulo>.', 1;
		END

		DECLARE @Mensaje VARCHAR(255) = (SELECT Mensaje FROM dbo.CodigosDeErrores WHERE CodigoDeError = @CodigoDeError);

		IF @Mensaje IS NULL
		BEGIN
			THROW 50700, '[Errores] Código de error desconocido.', 1;
		END

		SET @Detalles = ISNULL(@Detalles, '');

		DECLARE @Error VARCHAR(255) = CONCAT('[', @Modulo, '] ', @Mensaje, @Detalles, '.');

		THROW @CodigoDeError, @Error, 1;
	END TRY

	BEGIN CATCH
		PRINT ERROR_MESSAGE();
		THROW 50800, '[Errores] Error al lanzar la excepción.', 1;
	END CATCH
END
GO