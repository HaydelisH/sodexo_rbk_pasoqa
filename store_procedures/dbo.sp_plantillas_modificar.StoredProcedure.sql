USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_plantillas_modificar]    Script Date: 1/22/2024 7:21:15 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 06/08/2018
-- Descripcion: Actualiza los datos de una Plantilla 
-- Ejemplo:exec sp_plantillas_modificar 'modificar',1,'descripcion','titulo',1,1,'xxx','xxx','xxx',1
-- =============================================
CREATE PROCEDURE [dbo].[sp_plantillas_modificar]
	@pAccion CHAR(60),
	@idPlantilla INT,
	@Descripcion_Pl VARCHAR (MAX), 
	@Titulo_Pl VARCHAR (MAX),
	@idWF INT,
	@idTipoDoc INT,
	@RutModificador VARCHAR (10),
	@RutAprobador VARCHAR (10),
	@idCategoria INT, 
	@idTipoGestor INT
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;
	
	DECLARE @lmensaje	VARCHAR(100)
	DECLARE @error		INT
	DECLARE @total		INT;
			
    -- Insert statements for procedure here
	IF (@pAccion='modificar') 
    BEGIN
		IF EXISTS (SELECT idPlantilla FROM Plantillas WHERE idPlantilla = @idPlantilla AND Eliminado=0)
			BEGIN
				UPDATE Plantillas SET 
				Titulo_Pl = @Titulo_Pl,
				Descripcion_Pl = @Descripcion_Pl, 
				idWF = @idWF,
				idTipoDoc = @idTipoDoc,
				RutModificador = @RutModificador, 
				RutAprobador = @RutAprobador, 
				Aprobado = 0 ,
				idCategoria = @idCategoria,
				idTipoGestor = @idTipoGestor
				
				WHERE idPlantilla = @idPlantilla
				SELECT @lmensaje = ''
				SELECT @error = 0
			END 
		ELSE
			BEGIN
				SELECT @lmensaje = 'ESTA CATEGORIA NO EXISTE'
				SELECT @error = 1
			END 
    END 
    SELECT @error AS error, @lmensaje AS mensaje 
END
GO
