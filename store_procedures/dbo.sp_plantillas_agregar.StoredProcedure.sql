USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_plantillas_agregar]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 06/08/2018
-- Descripcion: Agrega una nueva Plantilla
-- Ejemplo:exec sp_plantillas_agregar 'agregar',1,'Descripcion','Titulo',1,1,'xxx','xxx','xxx'
-- =============================================
CREATE PROCEDURE [dbo].[sp_plantillas_agregar]
	@pAccion CHAR(60),
	@Descripcion_Pl VARCHAR(MAX),
	@Titulo_Pl VARCHAR (MAX),
	@idWorkflow INT,
	@idTipoDoc INT,
	@RutModificador VARCHAR(10),
	@RutAprobador VARCHAR(10),
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
    IF (@pAccion='agregar')  
    BEGIN				
		INSERT INTO Plantillas (Descripcion_Pl,Titulo_Pl,idWF, Aprobado,idTipoDoc,RutModificador,RutAprobador,idCategoria,idTipoGestor, Eliminado)
		VALUES
		(@Descripcion_Pl, @Titulo_Pl, @idWorkflow, 0, @idTipoDoc, @RutModificador,@RutAprobador,@idCategoria,@idTipoGestor, 0)
		SELECT @@IDENTITY AS idPlantilla
    END 
END
GO
