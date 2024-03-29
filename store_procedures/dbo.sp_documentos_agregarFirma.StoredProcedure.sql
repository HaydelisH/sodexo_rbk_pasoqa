USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_documentos_agregarFirma]    Script Date: 1/22/2024 7:21:13 PM ******/
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
CREATE PROCEDURE [dbo].[sp_documentos_agregarFirma]
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
	DECLARE @idWorkFlow INT;
    DECLARE @ConOrden INT
    DECLARE @estadoAnterior INT
	DECLARE @tipoCorreo INT
	DECLARE @CodCorreo INT
	DECLARE @RutFirmante VARCHAR(10)
	DECLARE @notifnuevousuario INT

    -- Insert statements for procedure here
    IF (@pAccion='agregar')  
    BEGIN
		--Consultar la primera ocurrencia de ese firmante en ese contrato
		SELECT TOP 1 @Orden_up = Orden  FROM ContratoFirmantes where idDocumento = @idDocumento AND RutFirmante = @personaid and Firmado = 0 Order by Orden ASC

		--Consultar la fecha incial del documento
		SELECT @FechaCreacion = FechaCreacion, @idWorkFlow = idWF FROM Contratos WHERE idDocumento = @idDocumento
		
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
		SELECT @estado = CF.idEstado, @estadoAnterior = CF.idEstado FROM ContratoFirmantes CF INNER JOIN ContratosEstados E ON CF.idEstado = E.idEstado WHERE CF.idDocumento = @idDocumento AND CF.RutFirmante = @personaid 

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
				--Agregar la fecha maxima registrada
				SELECT @FechaFirma = MAX(FechaFirma) FROM ContratoFirmantes WHERE idDocumento = @idDocumento

				UPDATE Contratos SET idEstado = 6, FechaUltimaFirma = @FechaFirma WHERE idDocumento = @idDocumento
                --Discrimino si el flujo pertenece a RRHH
                IF EXISTS(SELECT idWF FROM WorkflowProceso WHERE WorkflowProceso.idWF = @idWorkFlow AND WorkflowProceso.tipoWF IS NULL)
                    BEGIN
						--Enviar notificacion al correo
						INSERT INTO EnvioCorreos(documentoid,CodCorreo,RutUsuario) 
						SELECT idDocumento,6,Rut FROM ContratoDatosVariables 
						WHERE idDocumento = @idDocumento --AND idEstado NOT IN (2, 10, 11)

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
                --Discrimino si el flujo pertenece a RRLL
                ELSE IF EXISTS (SELECT idWF FROM WorkflowProceso WHERE WorkflowProceso.idWF = @idWorkFlow AND WorkflowProceso.tipoWF = 1)
                    BEGIN
						-- Solo enviara correo a los representantes de la institucion o sindicato
						INSERT INTO EnvioCorreos(documentoid,CodCorreo,RutUsuario) SELECT idDocumento,15,RutFirmante FROM ContratoFirmantes WHERE idDocumento = @idDocumento AND idEstado = 12
					END
			END	
		ELSE
			BEGIN
				--Consultar el estado cuando el de menor orden
				SELECT TOP 1 @estado = idEstado FROM ContratoFirmantes WHERE idDocumento = @idDocumento AND Firmado=0 ORDER BY Orden ASC
				
				--Actualizar el estado del documento con el proximo a firmar
				UPDATE Contratos SET idEstado= @estado	WHERE idDocumento = @idDocumento
				
                --Discrimino si el flujo pertenece a RRHH
                IF EXISTS(SELECT idWF FROM WorkflowProceso WHERE WorkflowProceso.idWF = @idWorkFlow AND WorkflowProceso.tipoWF IS NULL)
                    BEGIN
						--obtenenos el rut del firmante
						SELECT @RutFirmante = RutFirmante FROM ContratoFirmantes WHERE idDocumento =  @idDocumento AND idEstado = @estado ORDER BY Orden ASC
						--csb 26-05-2021 para rescatar marca si notificamos como nuevo usuario
						--si es 1 notificamos para crear nuevo usuario
						SET @notifnuevousuario = 0
						SELECT @notifnuevousuario = notifnuevousuario FROM usuarios WHERE usuarioid = @RutFirmante
                        --Enviar notificacion al correo
						--csb 26-05-2021 si es espera de firma empleado y no hemos notificado como nuevo usuario
						--se marca en enviacorreo para que notifique que tiene un documento pendiente de firma y que se cree como nuevo usuario 
						IF (@estado = 3 AND @notifnuevousuario  = 1 )
							BEGIN 
								INSERT INTO EnvioCorreos(documentoid,CodCorreo,RutUsuario) VALUES (@idDocumento,17,@RutFirmante)
								-- VIEJO INSERT INTO EnvioCorreos(documentoid,CodCorreo,RutUsuario) SELECT idDocumento,@estado,RutFirmante FROM ContratoFirmantes WHERE idDocumento = @idDocumento AND idEstado = @estado
								UPDATE usuarios SET notifnuevousuario = 2 WHERE usuarioid = @RutFirmante
							END
						ELSE
							BEGIN
						--csb 26-05-2021 para notificar que tiene un documento por firmar
						INSERT INTO EnvioCorreos(documentoid,CodCorreo,RutUsuario) VALUES (@idDocumento,@estado,@RutFirmante)
					END
				END
                --Discrimino si el flujo pertenece a RRLL
                ELSE IF EXISTS (SELECT idWF FROM WorkflowProceso WHERE WorkflowProceso.idWF = @idWorkFlow AND WorkflowProceso.tipoWF = 1)
                    BEGIN
                        SELECT @ConOrden = ISNULL(ConOrden, 0) FROM WorkflowEstadoProcesos WHERE idWorkflow = @idWorkFlow AND idEstadoWF = @estado
						IF (@estado = 12)
							BEGIN
								SET @tipoCorreo = 2
								SET @CodCorreo = 12
							END
						ELSE IF (@estado = 2)
							BEGIN
								SET @tipoCorreo = 2
								SET @CodCorreo = 16
							END
                        IF (@ConOrden = 1)
                            BEGIN
                                /*VIEJO --Enviar notificacion al correo
                                INSERT INTO EnvioCorreos(documentoid,CodCorreo,RutUsuario,TipoCorreo) SELECT TOP 1 idDocumento,@CodCorreo,RutFirmante,@tipoCorreo FROM ContratoFirmantes CF	WHERE idDocumento = @idDocumento AND idEstado= @estado AND Firmado = 0 ORDER BY OrdenMismoEstado 
								*/
								--obtenenos el rut del firmante
								SELECT TOP 1 @RutFirmante = RutFirmante FROM ContratoFirmantes WHERE idDocumento = @idDocumento AND idEstado = @estado AND Firmado = 0 ORDER BY OrdenMismoEstado
								--csb 26-05-2021 para rescatar marca si notificamos como nuevo usuario
								--si es 1 notificamos para crear nuevo usuario
								SET @notifnuevousuario = 0
								SELECT @notifnuevousuario = notifnuevousuario FROM usuarios WHERE usuarioid = @RutFirmante
								--Enviar notificacion al correo
								--csb 26-05-2021 si es espera de firma empleado y no hemos notificado como nuevo usuario
								--se marca en enviacorreo para que notifique que tiene un documento pendiente de firma y que se cree como nuevo usuario 
								--y marcamos para que no notifique como nuevo usuario
								IF (@estado = 12 AND @notifnuevousuario  = 1 )
									BEGIN 
										INSERT INTO EnvioCorreos(documentoid,CodCorreo,RutUsuario,TipoCorreo) VALUES (@idDocumento,30,@RutFirmante,@tipoCorreo)
										UPDATE usuarios SET notifnuevousuario = 2 WHERE usuarioid = @RutFirmante
									END
								ELSE
									BEGIN
										--csb 26-05-2021 para notificar que tiene un documento por firmar
										INSERT INTO EnvioCorreos(documentoid,CodCorreo,RutUsuario,TipoCorreo) VALUES (@idDocumento,@CodCorreo,@RutFirmante,@tipoCorreo)
									END
                            END
                        ELSE IF (@estado != @estadoAnterior)
                            BEGIN
								--obtenenos el rut del firmante
								--SELECT @RutFirmante = RutFirmante FROM ContratoFirmantes WHERE idDocumento = @idDocumento AND idEstado = @estado ORDER BY Orden ASC
								--csb 26-05-2021 para rescatar marca si notificamos como nuevo usuario
								--si es 1 notificamos para crear nuevo usuario
								--SET @notifnuevousuario = 0
								--SELECT @notifnuevousuario = notifnuevousuario FROM usuarios WHERE usuarioid = @RutFirmante
								--Enviar notificacion al correo
								--csb 26-05-2021 si es espera de firma empleado y no hemos notificado como nuevo usuario
								--se marca en enviacorreo para que notifique que tiene un documento pendiente de firma y que se cree como nuevo usuario 
								--y marcamos para que no notifique como nuevo usuario
								IF (@estado = 12) -- AND @notifnuevousuario  = 1 )
									BEGIN 
										--INSERT INTO EnvioCorreos(documentoid,CodCorreo,RutUsuario,TipoCorreo) VALUES (@idDocumento,30 o @CodCorreo,@RutFirmante,@tipoCorreo)
										--VIEJO INSERT INTO EnvioCorreos(documentoid,CodCorreo,RutUsuario,TipoCorreo) SELECT idDocumento,@CodCorreo,RutFirmante,@tipoCorreo FROM ContratoFirmantes WHERE idDocumento = @idDocumento AND idEstado = @estado ORDER BY Orden ASC
										INSERT INTO EnvioCorreos(documentoid,CodCorreo,RutUsuario,TipoCorreo) 
											SELECT 
												ContratoFirmantes.idDocumento,
												--@CodCorreo, --cambiar a 30 o @CodCorreo
												CASE usuarios.notifnuevousuario
													WHEN 1 THEN 30
													ELSE @CodCorreo
												END AS CodCorreo,
												ContratoFirmantes.RutFirmante,
												@tipoCorreo 
											FROM ContratoFirmantes 
											INNER JOIN usuarios ON usuarios.usuarioid = ContratoFirmantes.RutFirmante
											WHERE ContratoFirmantes.idDocumento = @idDocumento 
												AND ContratoFirmantes.idEstado = @estado 
											ORDER BY ContratoFirmantes.Orden ASC
										UPDATE usuarios SET notifnuevousuario = 2 WHERE usuarioid IN (
											SELECT 
												ContratoFirmantes.RutFirmante
											FROM ContratoFirmantes 
											INNER JOIN usuarios ON usuarios.usuarioid = ContratoFirmantes.RutFirmante
											WHERE ContratoFirmantes.idDocumento = @idDocumento 
												AND ContratoFirmantes.idEstado = @estado 
												AND usuarios.notifnuevousuario = 1
											--ORDER BY ContratoFirmantes.Orden ASC
										)
									END
								ELSE
									BEGIN
										INSERT INTO EnvioCorreos(documentoid,CodCorreo,RutUsuario,TipoCorreo) SELECT idDocumento,@CodCorreo,RutFirmante,@tipoCorreo FROM ContratoFirmantes WHERE idDocumento = @idDocumento AND idEstado = @estado ORDER BY Orden ASC
									END
                            END
                    END	
			END
    END 
	
END
GO
