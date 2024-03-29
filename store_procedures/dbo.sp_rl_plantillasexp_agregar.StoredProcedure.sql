USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_rl_plantillasexp_agregar]    Script Date: 1/22/2024 7:21:15 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 06/08/2018
-- Descripcion: Agrega una nueva Plantilla
-- Modificado por: Gdiaz 11/01/2021
-- Ejemplo:exec sp_plantillas_agregar 'agregar',1,'Descripcion','Titulo',1,1,'xxx','xxx','xxx','xxx'
-- =============================================
CREATE PROCEDURE [dbo].[sp_rl_plantillasexp_agregar]
	@pAccion CHAR(60),
	@Descripcion_Pl VARCHAR(MAX),
	@Titulo_Pl VARCHAR (MAX),
	@idWorkflow INT,
	@idTipoDoc INT,
	@RutModificador VARCHAR(10),
	@RutAprobador VARCHAR(10),
	@idCategoria INT,
	@idTipoGestor INT,
	@RutEmpresa VARCHAR(10)
	
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;
	
	DECLARE @mensaje	VARCHAR(100)
	DECLARE @error		INT
	DECLARE @total		INT;
	DECLARE @idplantilla INT;

	DECLARE @nomtipodoc varchar(50);
	DECLARE @nomwfl		varchar(50);
	DECLARE @nomtipogestor varchar (60);
	DECLARE @descripcion VARCHAR (200);


			
    -- Insert statements for procedure here
    IF (@pAccion='agregar')  
    BEGIN	
	
		IF EXISTS ( SELECT PL.Titulo_Pl 
		FROM Plantillas AS PL
		INNER JOIN PlantillasEmpresa AS PE ON PE.idPlantilla = PL.idPlantilla
		WHERE PE.RutEmpresa = @RutEmpresa
		AND PL.idTipoDoc = @idTipoDoc
		AND PL.idTipoGestor = @idTipoGestor
		AND PL.idWF = @idWorkflow )
		BEGIN
			SELECT 1 as error, 'Flujo ya creado para el tipo de documento' as mensaje
			RETURN
		END
	
		BEGIN
			SELECT @nomtipodoc		= NombreTipoDoc FROM TipoDocumentos WHERE idTipoDoc =  @idTipoDoc
			SELECT @nomwfl			= NombreWF FROM WorkflowProceso WHERE idWF = @idWorkflow
			SELECT @nomtipogestor	= Nombre FROM TipoGestor WHERE idTipoGestor = @idTipoGestor

			SET @descripcion  =  @nomtipogestor + ' - ('  +  @nomwfl + ')'

			BEGIN TRANSACTION 
			BEGIN TRY	
				

				INSERT INTO Plantillas (Descripcion_Pl,Titulo_Pl,idWF, Aprobado,idTipoDoc,RutModificador,RutAprobador,idCategoria,idTipoGestor, Eliminado,tipogeneracion)
				VALUES
				(@descripcion, @Titulo_Pl, @idWorkflow, 1, @idTipoDoc, @RutModificador,@RutAprobador,@idCategoria,@idTipoGestor, 0,1)
				SET @idplantilla =  @@IDENTITY 

				INSERT INTO PlantillasEmpresa (idPlantilla, RutEmpresa)
				VALUES (@idplantilla,@RutEmpresa)

				SET @error		= 0
				SET @mensaje	= ''

			COMMIT TRANSACTION
			END TRY

			BEGIN CATCH
			ROLLBACK TRANSACTION 
	
				SET @error		= ERROR_NUMBER()
				SET @mensaje	= ERROR_MESSAGE()
	
			END CATCH
		END
    END 

	SELECT @error as error, @mensaje as mensaje

END
GO
