USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_plantillas_eliminar]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 06/08/2018
-- Descripcion: Elimina de forma logica uuna Plantilla
-- Ejemplo:exec sp_plantillas_eliminar
-- =============================================
CREATE PROCEDURE [dbo].[sp_plantillas_eliminar]
	@pAccion CHAR(60),
	@idPlantilla INT
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;
	
	DECLARE @lmensaje	VARCHAR(100)
	DECLARE @error		INT
	DECLARE @total		INT;
			
    -- Insert statements for procedure here
	IF (@pAccion='eliminar') 
    BEGIN
        IF NOT EXISTS (SELECT idDocumento FROM Contratos WHERE Idplantilla = @idPlantilla)
        BEGIN
            IF EXISTS (SELECT idPlantilla FROM Plantillas WHERE idPlantilla = @idPlantilla AND Eliminado=0)
                BEGIN
                    UPDATE Plantillas SET Eliminado = 1
                    WHERE idPlantilla = @idPlantilla
                    
                    DELETE FROM PlantillasEmpresa WHERE idPlantilla = @idPlantilla
                    
                    SELECT @lmensaje = ''
                    SELECT @error = 0
                END 
            ELSE
                BEGIN
                    SELECT @lmensaje = 'ESTA PLANTILLA NO EXISTE'
                    SELECT @error = 1
                END 
        END 
        ELSE
        BEGIN
            SELECT @lmensaje = 'No se puede borrar, esta plantilla tiene documentos asociados'
            SELECT @error = 1
        END 
    END 
    SELECT @error AS error, @lmensaje AS mensaje 
END
GO
