USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_documentos_modifica_aprobador]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS OFF
GO
SET QUOTED_IDENTIFIER OFF
GO
-- =============================================
-- Autor: Haydelis Hernandez 
-- Creado el: 21/09/2018
-- Descripcion: Actualiza los datos del aprobador del documento 
-- Ejemplo:exec sp_documentos_modifica_aprobador 'xxxx'
-- =============================================
-- modificado gdb 14-07-2021 agregar nueva notificacion de creacion de usuario
-- cuando tiene un nuevo documento por firmar
-- =============================================
CREATE PROCEDURE [dbo].[sp_documentos_modifica_aprobador]
@pidDocumento		VARCHAR(10)	-- id del contrato

AS	
BEGIN
	SET NOCOUNT ON;

 	DECLARE @error		INT
	DECLARE @mensaje	VARCHAR(100)
	DECLARE @nuevo_estado INT
	DECLARE @ConOrden INT
	DECLARE @idWorkFlow INT;
	DECLARE @idTipoFirma INT;
	DECLARE @tipoCorreo INT
	DECLARE @CodCorreo INT
	DECLARE @RutFirmante VARCHAR(10)
	DECLARE @notifnuevousuario INT

	IF EXISTS(SELECT idDocumento FROM Contratos WHERE idDocumento= @pidDocumento) 
		BEGIN 
			SELECT @idTipoFirma = idTipoFirma FROM Contratos WHERE idDocumento= @pidDocumento
			IF (@idTipoFirma = 2)
				BEGIN 
					--Buscar el flujo del documento 
					SELECT @idWorkFlow = idWF FROM Contratos WHERE idDocumento = @pidDocumento
					
					--Buscar el primer estado del flujo
					SELECT @nuevo_estado = idEstadoWF, @ConOrden = ISNULL(ConOrden, 0) FROM WorkflowEstadoProcesos WHERE idWorkflow = @idWorkFlow AND Orden = 1
					
					UPDATE Contratos SET 
					idEstado = @nuevo_estado
					WHERE idDocumento = @pidDocumento

					--Discrimino si el flujo pertenece a RRHH
					IF EXISTS(SELECT idWF FROM WorkflowProceso WHERE WorkflowProceso.idWF = @idWorkFlow AND WorkflowProceso.tipoWF IS NULL)
						BEGIN
							--obtenenos el rut del firmante
							SELECT @RutFirmante = RutFirmante FROM ContratoFirmantes WHERE idDocumento = @pidDocumento AND idEstado = @nuevo_estado ORDER BY Orden ASC
							--csb 26-05-2021 para rescatar marca si notificamos como nuevo usuario
							--si es 1 notificamos para crear nuevo usuario
							SET @notifnuevousuario = 0
							SELECT @notifnuevousuario = notifnuevousuario FROM usuarios WHERE usuarioid = @RutFirmante
							--Enviar notificacion al correo
							--csb 26-05-2021 si es espera de firma empleado y no hemos notificado como nuevo usuario
							--se marca en enviacorreo para que notifique que tiene un documento pendiente de firma y que se cree como nuevo usuario 
							--y marcamos para que no notifique como nuevo usuario
							IF (@nuevo_estado = 3 AND @notifnuevousuario  = 1 )
								BEGIN 
									INSERT INTO EnvioCorreos(documentoid,CodCorreo,RutUsuario) VALUES (@pidDocumento,17,@RutFirmante)
									--INSERT INTO EnvioCorreos(documentoid,CodCorreo,RutUsuario) SELECT idDocumento,@nuevo_estado,RutFirmante FROM ContratoFirmantes WHERE idDocumento = @pidDocumento AND idEstado = @nuevo_estado ORDER BY Orden ASC
									UPDATE usuarios SET notifnuevousuario = 2 WHERE usuarioid = @RutFirmante
								END
							ELSE
								BEGIN
									--csb 26-05-2021 para notificar que tiene un documento por firmar
									INSERT INTO EnvioCorreos(documentoid,CodCorreo,RutUsuario) VALUES (@pidDocumento,@nuevo_estado,@RutFirmante)
								END
							SELECT @error= 0
							SELECT @mensaje = ''				
						END	
					--Discrimino si el flujo pertenece a RRLL
					ELSE IF EXISTS (SELECT idWF FROM WorkflowProceso WHERE WorkflowProceso.idWF = @idWorkFlow AND WorkflowProceso.tipoWF = 1)
						BEGIN
							IF (@nuevo_estado = 12)
								BEGIN
									SET @tipoCorreo = 2
									SET @CodCorreo = 12
								END
							ELSE IF (@nuevo_estado = 2)
								BEGIN
									SET @tipoCorreo = 2 -- 0
									SET @CodCorreo = 16
								END
							IF (@ConOrden = 1)
								BEGIN
									/*VIEJO --Enviar notificacion al correo
									INSERT INTO EnvioCorreos(documentoid,CodCorreo,RutUsuario,TipoCorreo) SELECT TOP 1 idDocumento,@CodCorreo,RutFirmante,@tipoCorreo FROM ContratoFirmantes CF	WHERE idDocumento = @pidDocumento AND idEstado= @nuevo_estado AND Firmado = 0 ORDER BY OrdenMismoEstado 
									*/
									--obtenenos el rut del firmante
									SELECT TOP 1 @RutFirmante = RutFirmante FROM ContratoFirmantes WHERE idDocumento = @pidDocumento AND idEstado = @nuevo_estado AND Firmado = 0 ORDER BY OrdenMismoEstado
									--csb 26-05-2021 para rescatar marca si notificamos como nuevo usuario
									--si es 1 notificamos para crear nuevo usuario
									SET @notifnuevousuario = 0
									SELECT @notifnuevousuario = notifnuevousuario FROM usuarios WHERE usuarioid = @RutFirmante
									--Enviar notificacion al correo
									--csb 26-05-2021 si es espera de firma empleado y no hemos notificado como nuevo usuario
									--se marca en enviacorreo para que notifique que tiene un documento pendiente de firma y que se cree como nuevo usuario 
									--y marcamos para que no notifique como nuevo usuario
									IF (@nuevo_estado = 12 AND @notifnuevousuario  = 1 )
										BEGIN 
											INSERT INTO EnvioCorreos(documentoid,CodCorreo,RutUsuario,TipoCorreo) VALUES (@pidDocumento,30,@RutFirmante,@tipoCorreo)
											UPDATE usuarios SET notifnuevousuario = 2 WHERE usuarioid = @RutFirmante
										END
									ELSE
										BEGIN
											--csb 26-05-2021 para notificar que tiene un documento por firmar
											INSERT INTO EnvioCorreos(documentoid,CodCorreo,RutUsuario,TipoCorreo) VALUES (@pidDocumento,@CodCorreo,@RutFirmante,@tipoCorreo)
										END
									SELECT @error= 0
									SELECT @mensaje = ''	
								END
							ELSE
								BEGIN
									--obtenenos el rut del firmante
									--SELECT @RutFirmante = RutFirmante FROM ContratoFirmantes WHERE idDocumento = @pidDocumento AND idEstado = @nuevo_estado ORDER BY Orden ASC
									--csb 26-05-2021 para rescatar marca si notificamos como nuevo usuario
									--si es 1 notificamos para crear nuevo usuario
									--SET @notifnuevousuario = 0
									--SELECT @notifnuevousuario = notifnuevousuario FROM usuarios WHERE usuarioid = @RutFirmante
									--Enviar notificacion al correo
									--csb 26-05-2021 si es espera de firma empleado y no hemos notificado como nuevo usuario
									--se marca en enviacorreo para que notifique que tiene un documento pendiente de firma y que se cree como nuevo usuario 
									--y marcamos para que no notifique como nuevo usuario
									IF (@nuevo_estado = 12) -- AND @notifnuevousuario  = 1 )
										BEGIN 
											--INSERT INTO EnvioCorreos(documentoid,CodCorreo,RutUsuario,TipoCorreo) VALUES (@pidDocumento,30 o @CodCorreo,@RutFirmante,@tipoCorreo)
											--VIEJO INSERT INTO EnvioCorreos(documentoid,CodCorreo,RutUsuario,TipoCorreo) SELECT idDocumento,@CodCorreo,RutFirmante,@tipoCorreo FROM ContratoFirmantes WHERE idDocumento = @pidDocumento AND idEstado = @nuevo_estado ORDER BY Orden ASC
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
												WHERE ContratoFirmantes.idDocumento = @pidDocumento 
													AND ContratoFirmantes.idEstado = @nuevo_estado 
												ORDER BY ContratoFirmantes.Orden ASC
											UPDATE usuarios SET notifnuevousuario = 2 WHERE usuarioid IN (
												SELECT 
													ContratoFirmantes.RutFirmante
												FROM ContratoFirmantes 
												INNER JOIN usuarios ON usuarios.usuarioid = ContratoFirmantes.RutFirmante
												WHERE ContratoFirmantes.idDocumento = @pidDocumento 
													AND ContratoFirmantes.idEstado = @nuevo_estado 
													AND usuarios.notifnuevousuario = 1
												--ORDER BY ContratoFirmantes.Orden ASC
											)
										END
									ELSE
										BEGIN
											INSERT INTO EnvioCorreos(documentoid,CodCorreo,RutUsuario,TipoCorreo) SELECT idDocumento,@CodCorreo,RutFirmante,@tipoCorreo FROM ContratoFirmantes WHERE idDocumento = @pidDocumento AND idEstado = @nuevo_estado ORDER BY Orden ASC
										END
									SELECT @error= 0
									SELECT @mensaje = ''	
								END
						END	
				END
			ELSE
				BEGIN
					UPDATE Contratos SET 
					idEstado = 4 -- Generado Manualmente
					WHERE idDocumento = @pidDocumento
				END
		END
	ELSE
		BEGIN
			SELECT @mensaje = 'Contrato no existe'
			SELECT @error = 1			
		END			
		
	SELECT @error AS error, @mensaje AS mensaje;
END
GO
