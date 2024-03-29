USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_documentos_agregar_20210920]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 26/06/2018
-- Descripcion: Agrega un nuevo Contrato 
-- Ejemplo:exec sp_documentos_agregar 'agregar',1,1,1,1,'2018-06-26',1,1,'xxxxxxxx-x',1,'30 Dias',10,1,1,''
-- =============================================
CREATE PROCEDURE [dbo].[sp_documentos_agregar_20210920]
	@pAccion CHAR(60),
	@idEstado INT,
	@idWorkFlow INT,
	@FechaCreacion DATE,
	@idTipoFirma INT,
	@idPlantilla INT,
	@DocCode VARCHAR(50),
	@Observacion VARCHAR(200),
	@idProceso INT,
	@idTipoGeneracion INT,
	@pRutEmpresa VARCHAR(10)
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;
	
	DECLARE @lmensaje	VARCHAR(100)
	DECLARE @error		INT
	DECLARE @nuevo_estado INT
	DECLARE @total		INT
	DECLARE @ID			INT;
			
   
    IF (@pAccion='agregar')  
    BEGIN
		--Si es de firma Manual
		/*IF ( @idTipoFirma = 1 )
			BEGIN 
				--Buscar el primer estado del flujo
				SELECT @nuevo_estado = idEstadoWF FROM WorkflowEstadoProcesos WHERE idWorkflow = @idWorkFlow AND Orden = 1
				
				SET @idEstado = @nuevo_estado
			END*/
	
		--Insertar en Contratos			
		INSERT INTO Contratos(
			idEstado,
			idWF, 
			FechaCreacion, 
			idTipoFirma, 
			idPlantilla, 
			DocCode,
			Eliminado,
			Observacion,
			idProceso, 
			Enviado, 
			idTipoGeneracion,
			RutEmpresa
		)VALUES(
			@idEstado,
			@idWorkFlow,
			GETDATE(),
			@idTipoFirma,
			@idPlantilla,
			@DocCode,
			0,
			@Observacion,
			@idProceso,
			0,
			@idTipoGeneracion,
			@pRutEmpresa
		)
        SELECT @@IDENTITY AS idDocumento  
		
		--Envio de notificacion de correos 
       -- INSERT INTO EnvioCorreos(documentoid, CodCorreo) VALUES(@@IDENTITY ,@idEstado)   
		
	END 
	
END
GO
