USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_empleadoFormulario_sendEmail]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez 
-- Creado el: 25/03/2019
-- Descripcion: Obtiene las variables diponibles de un documento subido por carga masiva 
-- Ejemplo:exec [sp_empleadoFormulario_sendEmail] 
-- =============================================
CREATE PROCEDURE [dbo].[sp_empleadoFormulario_sendEmail]
	@Rut VARCHAR(10),
    @empleadoFormularioid integer,
	@CodCorreo integer,
    @tipoCorreo integer
AS
BEGIN
	SET NOCOUNT ON;

    DECLARE @lmensaje VARCHAR(100)
	DECLARE @error INT;
    
    BEGIN TRANSACTION 
	    BEGIN TRY
            INSERT INTO EnvioCorreos (CodCorreo,documentoid,RutUsuario,TipoCorreo) 
            VALUES (
                @CodCorreo, @empleadoFormularioid, @Rut, @tipoCorreo
                --13, @empleadoFormularioid, @Rut, 3
            )
    	    COMMIT TRANSACTION
	    END TRY

	    BEGIN CATCH
	        ROLLBACK TRANSACTION 
		
            SET @error		= ERROR_NUMBER()
            SET @lmensaje	= ERROR_MESSAGE()
			
	    END CATCH
	
	SELECT @error AS error, @lmensaje AS mensaje
END
GO
