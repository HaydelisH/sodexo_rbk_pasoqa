USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_procesos_eliminar]    Script Date: 1/22/2024 7:21:15 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 04-04-2019
-- Descripcion:  Elimina de forma logica un Proceso
-- Ejemplo:exec sp_procesos_eliminar 'eliminar',1
-- =============================================
CREATE PROCEDURE [dbo].[sp_procesos_eliminar]
	@pAccion CHAR(60),
	@idProceso INT
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
		IF  NOT EXISTS ( SELECT idProceso FROM Procesos WHERE idProceso = @idProceso )
			BEGIN
				SELECT @lmensaje = 'ESTE PROCESO NO EXISTE'
				SELECT @error = 1
			END
		ELSE
            BEGIN
                IF NOT EXISTS (SELECT idDocumento FROM Contratos WHERE idProceso = @idProceso)
                    BEGIN
                        UPDATE Procesos SET Eliminado = 1
                        WHERE idProceso = @idProceso
                        
                        SELECT @lmensaje = ''
                        SELECT @error = 0
                    END
                ELSE
                    BEGIN
                        SELECT @lmensaje = 'No se puede borrar, este proceso esta asociado a documentos.'
                        SELECT @error = 1
                    END
            END
    END 
    SELECT @error AS error, @lmensaje AS mensaje 
END
GO
