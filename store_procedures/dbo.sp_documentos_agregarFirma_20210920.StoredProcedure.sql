USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_documentos_agregarFirma_20210920]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 13/07/2018
-- Descripcion: Agrega un nuevo Contrato 
-- Ejemplo:exec sp_documentos_agregarFirma 'agregar','xxxxxxxx-x',1,'2018-07-13'
-- =============================================
CREATE PROCEDURE [dbo].[sp_documentos_agregarFirma_20210920]
	@pAccion CHAR(60),
	@personaid VARCHAR(10),
	@idDocumento INT, 
	@FechaFirma DATETIME	
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;
	
	DECLARE @lmensaje	VARCHAR(100)
	DECLARE @error		INT
	DECLARE @total		INT
	DECLARE @FechaCreacion DATE
	DECLARE @dias INT
	DECLARE @estado INT
	DECLARE @orden INT
	DECLARE @Orden_up INT;
	DECLARE @rutPostulante	VARCHAR(10)
		
    -- Insert statements for procedure here
    IF (@pAccion='agregar')  
    BEGIN
		--Consultar la primera ocurrencia de ese firmante en ese contrato
		SELECT TOP 1 @Orden_up = Orden  FROM ContratoFirmantes where idDocumento = @idDocumento AND RutFirmante = @personaid and Firmado = 0 Order by Orden ASC

		--Consultar la fecha incial del documento
		SELECT @FechaCreacion = FechaCreacion FROM Contratos WHERE idDocumento = @idDocumento
		
		--Calcular Dias en el que firmo 
		SELECT @dias = DATEDIFF(day, @FechaCreacion, @FechaFirma)
		
		--Actualizar Datos de Firma en Contrato Firmantes		
		UPDATE ContratoFirmantes
			SET
				Firmado = 1,
				FechaFirma = @FechaFirma,
				DiasTardoFirma = @dias
			WHERE 
				idDocumento = @idDocumento 
				AND 
				RutFirmante = @personaid 
				AND
				Orden = @Orden_up
		
		--Consultar el estado del que se esta firmando 
		SELECT @estado = CF.idEstado FROM ContratoFirmantes CF INNER JOIN ContratosEstados E ON CF.idEstado = E.idEstado WHERE CF.idDocumento = @idDocumento AND CF.RutFirmante = @personaid 

		--Consultar si No quedan firmantes en ese Estado 
		IF NOT EXISTS ( SELECT idEstado FROM ContratoFirmantes WHERE idDocumento = @idDocumento AND Firmado = 0 AND idEstado = @estado ) 
			BEGIN 
				--Buscar siguiente estado
				SELECT TOP 1 @estado = idEstado FROM ContratoFirmantes WHERE idDocumento = @idDocumento AND Firmado = 0 ORDER BY Orden

				--Cambiar de Estado el Documento 
				UPDATE Contratos SET idEstado = @estado WHERE idDocumento = @idDocumento
						
			END	
			
		--Contar cuantos firmantes quedan pendientes por firma
		SELECT @total = COUNT(idDocumento) FROM ContratoFirmantes WHERE idDocumento = @idDocumento	AND Firmado=0
		
		--Si no quedan firmantes, pasar el estado del Documento a Aprobado
		IF ( @total = 0 )
			BEGIN
				UPDATE Contratos SET idEstado = 6, FechaUltimaFirma = @FechaFirma WHERE idDocumento = @idDocumento
				--Enviar notificacion al correo
				INSERT INTO EnvioCorreos(documentoid,CodCorreo,RutUsuario) 
				SELECT idDocumento,6,Rut FROM ContratoDatosVariables 
				WHERE idDocumento = @idDocumento --*Hh que envie el documento firmado , solo al trabajador

                -- Actualizar campo Postulante.contratado
                SELECT @rutPostulante = ContratoDatosVariables.rut 
                    FROM contratos 
                    INNER JOIN Plantillas 
                        ON Plantillas.idPlantilla = contratos.idPlantilla 
                        AND Plantillas.idTipoDoc = 1 -- Contrato de trabajo
                        AND Plantillas.idTipoGestor = 10044 -- Contrato Trabajo    
                        AND contratos.idEstado = 6
                        AND contratos.idDocumento = @idDocumento
                    INNER JOIN ContratoDatosVariables 
                        ON ContratoDatosVariables.idDocumento = contratos.idDocumento 
                UPDATE Postulantes SET contratado = 1 WHERE Postulantes.rut = @rutPostulante
			END	
		ELSE
			BEGIN
				--Consultar el estado cuando el de menor orden
				SELECT TOP 1 @estado = idEstado FROM ContratoFirmantes WHERE idDocumento = @idDocumento AND Firmado=0 ORDER BY Orden ASC
				
				--Actualizar el estado del documento con el proximo a firmar
				UPDATE Contratos SET idEstado= @estado	WHERE idDocumento = @idDocumento
				
				--Enviar notificacion al correo
				INSERT INTO EnvioCorreos(documentoid,CodCorreo,RutUsuario) SELECT idDocumento,@estado,RutFirmante FROM ContratoFirmantes WHERE idDocumento = @idDocumento AND idEstado = @estado
				
			END
    END 
	
END
GO
