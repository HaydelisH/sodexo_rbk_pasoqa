USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_setGenericaFormulario_insert]    Script Date: 1/22/2024 7:21:15 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 11/06/2019
-- Descripcion: Agregar datos de empleados con Usuario

-- Ejemplo:exec sp_setGenericaFormulario_insert
-- =============================================
CREATE PROCEDURE [dbo].[sp_setGenericaFormulario_insert]
	@idFormulario INTEGER,
	@idDocumento INTEGER,
	@personaId VARCHAR(10),
	@personaEmail VARCHAR(60),
	@personaNombre VARCHAR(110),
	@personaCelular VARCHAR(20),
	@parentescoRut VARCHAR(10),
	@parentescoNombre VARCHAR(110),
	@parentesco VARCHAR(50),
	@parentescoNacimiento DATETIME,
	@parentescoEmail VARCHAR(60),
	@parentescoCelular VARCHAR(20),
	@parentescoGenero VARCHAR(20),
	@parentescoTipoCarga VARCHAR(50)
AS
BEGIN
	

	SET NOCOUNT ON;
	
	DECLARE @lmensaje	VARCHAR(100)
	DECLARE @error		INT

	BEGIN TRANSACTION 
	BEGIN TRY
	
		--PERSONAS	
        INSERT INTO GenericaFormulario(
                idFormulario,
                idDocumento,
                personaId,
                personaEmail,
                personaNombre,
                personaCelular,
                parentescoRut,
                parentescoNombre,
                parentesco,
                parentescoNacimiento,
                parentescoEmail,
                parentescoCelular,
                parentescoGenero,
                parentescoTipoCarga
            )VALUES(
                @idFormulario,
                @idDocumento,
                @personaId,
                @personaEmail,
                @personaNombre,
                @personaCelular,
                @parentescoRut,
                @parentescoNombre,
                @parentesco,
                @parentescoNacimiento,
                @parentescoEmail,
                @parentescoCelular,
                @parentescoGenero,
                @parentescoTipoCarga
            )
                
        SELECT @lmensaje = ''
        SELECT @error = 0			
                        
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
