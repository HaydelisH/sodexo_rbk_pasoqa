USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_clausulas_eliminar]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 06/01/2018
-- Descripcion: Elimina de forma logica una Clausula
-- Ejemplo:exec sp_clausulas_eliminar 'eliminar',1
-- =============================================
CREATE PROCEDURE [dbo].[sp_clausulas_eliminar]
	@pAccion CHAR(60),
	@idClausula INT
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
        IF NOT EXISTS (SELECT idClausula FROM PlantillasClausulas WHERE idClausula = @idClausula)
        BEGIN
            IF EXISTS (SELECT idClausula FROM Clausulas WHERE idClausula = @idClausula AND Eliminado=0)
                BEGIN
                    UPDATE Clausulas SET Eliminado = 1
                    WHERE idClausula = @idClausula
                    SELECT @lmensaje = ''
                    SELECT @error = 0
                END 
            ELSE
                BEGIN
                    SELECT @lmensaje = 'ESTA CATEGORIA NO EXISTE'
                    SELECT @error = 1
                END 
        END
        ELSE
        BEGIN
            SELECT @lmensaje = 'No se puede borrar, la clausula participa en mas de una Plantilla'
            SELECT @error = 1
        END 
    END 
    SELECT @error AS error, @lmensaje AS mensaje 
END
GO
