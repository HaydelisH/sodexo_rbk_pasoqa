USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_plantillas_obtener_20210920]    Script Date: 1/22/2024 7:21:15 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 06/08/2018
-- Descripcion: Obtinee los datos de una Plantilla
-- Ejemplo:exec sp_plantillas_obtener 1
-- =============================================
CREATE PROCEDURE [dbo].[sp_plantillas_obtener_20210920]
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
   
    BEGIN
		IF EXISTS (SELECT idPlantilla FROM Plantillas WHERE (idPlantilla=@idPlantilla AND Eliminado=0))
			BEGIN
				SELECT
 					Plantillas.idPlantilla,
					Plantillas.Titulo_Pl,
					Plantillas.Descripcion_Pl,
					WorkflowProceso.idWF,
					WorkflowProceso.NombreWF,
					TipoDocumentos.idTipoDoc,
					TipoDocumentos.NombreTipoDoc,
					Categorias.idCategoria,
					Categorias.Titulo,
					Plantillas.Aprobado,
					Plantillas.idTipoGestor,
					TG.Nombre
				FROM
					PLantillas
				INNER JOIN
					WorkflowProceso
				ON
					Plantillas.idWF = WorkflowProceso.idWF 
				INNER JOIN 
					TipoDocumentos
				ON 	
					Plantillas.idTipoDoc = TipoDocumentos.idTipoDoc
			
				INNER JOIN 
					Categorias
				ON
					Plantillas.idCategoria = Categorias.idCategoria
				INNER JOIN 
					TipoGestor TG
				ON 
					TG.idTipoGestor = Plantillas.idTipoGestor
				WHERE	
					Plantillas.Eliminado = 0
					AND
					Plantillas.idPlantilla = @idPlantilla
                         
			END 
	END
END
GO
