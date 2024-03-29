USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_documentos_envioGestor_20201016]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[sp_documentos_envioGestor_20201016]
	@piddocumento INT
AS
BEGIN	
	SET NOCOUNT ON;	
	
	DECLARE @iddoc INT
	DECLARE @personaid VARCHAR(10)
	DECLARE @cc	NVARCHAR(14)
	DECLARE @em VARCHAR(10)
	DECLARE @lp NVARCHAR(14)
	DECLARE @error		INT
	DECLARE @mensaje	VARCHAR(200);		
  	
  	--REVISAR Y COLOCAR NOMBRES IGUALES EN CENTROS DE COSTO Y LUGARES DE PAGO

	--Consultamos los datos necesarios 
	SELECT 
		@personaid = CDV.Rut,  --Persona 
		@cc = CDV.CentroCosto, --Centro de costo 
		@em = C.RutEmpresa     --Empresa
	FROM Contratos C
	INNER JOIN ContratoDatosVariables CDV ON C.idDocumento = CDV.idDocumento
	WHERE C.idDocumento = @piddocumento

	--Si falta algun dato, nos vamos 
	IF ( @personaid = '' OR @cc = '' OR @em = '' )
		BEGIN 
			SELECT @error = 1
			SELECT @mensaje = 'No se pudo enviar al Gestor porque faltan datos'
			SELECT @error as error , @mensaje as mensaje 
			RETURN
		END
	
	--PERSONA
	BEGIN TRY
		IF NOT EXISTS ( SELECT personaid FROM [Demo_Gestor].[dbo].[personas] WHERE personaid = @personaid )
			BEGIN 
				INSERT INTO [Demo_Gestor].[dbo].[personas](personaid, nombre, appaterno, apmaterno, nacionalidad, estadocivil, fechanacimiento, direccion, comuna, ciudad, correo)
					SELECT 
						P.personaid, 
						P.nombre,
						P.appaterno,
						P.apmaterno,
						P.nacionalidad,
					   CASE 
							WHEN p.estadocivil = 1 THEN 'Soltero(a)'
							WHEN p.estadocivil = 2 THEN 'Casado(a)'
							WHEN p.estadocivil = 3 THEN 'Divorciado(a)'
							WHEN p.estadocivil = 4 THEN 'Viudo(a)'
						END As estadocivil,
						P.fechanacimiento ,
						P.direccion,
						P.comuna,
						P.ciudad,
						P.correo
					FROM personas P

					WHERE
						P.personaid = @personaid
			END 
		ELSE
			BEGIN 
				UPDATE [Demo_Gestor].[dbo].[personas] SET 
					   [Demo_Gestor].[dbo].[personas].personaid = p.personaid,
					   [Demo_Gestor].[dbo].[personas].nombre = p.nombre,
					   [Demo_Gestor].[dbo].[personas].appaterno = p.appaterno,
					   [Demo_Gestor].[dbo].[personas].apmaterno = p.apmaterno,
					   [Demo_Gestor].[dbo].[personas].nacionalidad = p.nacionalidad,
					   [Demo_Gestor].[dbo].[personas].estadocivil = 
					   CASE 
							WHEN per.estadocivil = 1 THEN 'Soltero(a)'
							WHEN per.estadocivil = 2 THEN 'Casado(a)'
							WHEN per.estadocivil = 3 THEN 'Divorciado(a)'
							WHEN per.estadocivil = 4 THEN 'Viudo(a)'
						END,
						[Demo_Gestor].[dbo].[personas].fechanacimiento = p.fechanacimiento,
						[Demo_Gestor].[dbo].[personas].direccion = p.direccion,
						[Demo_Gestor].[dbo].[personas].comuna= p.comuna,
						[Demo_Gestor].[dbo].[personas].ciudad = p.ciudad,
						[Demo_Gestor].[dbo].[personas].correo = p.correo
					   
				FROM [Demo_Gestor].[dbo].[personas] per
					INNER JOIN personas p  ON per.personaid = p.personaid
				WHERE 
					p.personaid = @personaid
			END
			
	END TRY  
	BEGIN CATCH  
		SET @error		= ERROR_NUMBER()
		--SET @mensaje	= ERROR_MESSAGE()
		SET @mensaje = 'No se pudo enviar al Gestor por error con los datos personales del Empleado'
	END CATCH 

	--EMPRESA
	BEGIN TRY	
		IF NOT EXISTS ( SELECT empresaid FROM [Demo_Gestor].[dbo].[empresas] WHERE empresaid = @em )
			BEGIN
				INSERT INTO [Demo_Gestor].[dbo].[empresas](
					empresaid,
					nombre,
					direccion,
					comuna,
					ciudad)
				SELECT 
					rutempresa,
					RazonSocial,
					Direccion,
					Comuna,
					Ciudad
				FROM 
					Empresas 
				WHERE 
					RutEmpresa = @em
			END
		ELSE
			BEGIN 				
				 UPDATE [Demo_Gestor].[dbo].[empresas] SET 
					[Demo_Gestor].[dbo].[empresas].nombre = E.RazonSocial,
					[Demo_Gestor].[dbo].[empresas].direccion = E.Direccion,
					[Demo_Gestor].[dbo].[empresas].comuna = E.Comuna,
					[Demo_Gestor].[dbo].[empresas].ciudad = E.Ciudad
				  FROM [Demo_Gestor].[dbo].[empresas] em
				  INNER JOIN Empresas E ON em.empresaid = E.RutEmpresa
				  WHERE 
					E.RutEmpresa = @em
			END
	END TRY  
	BEGIN CATCH  
		SET @error		= ERROR_NUMBER()
		--SET @mensaje	= ERROR_MESSAGE()
		SET @mensaje = 'No se pudo enviar al Gestor por error con los datos de Empresa'
	END CATCH 
	
	--LUGAR DE PAGO
	/*IF NOT EXISTS( SELECT lugarpagoid FROM [Demo_Gestor].[dbo].[lugarespago] WHERE lugarpagoid = @lp AND empresaid = @em ) 
		BEGIN
			INSERT INTO [Demo_Gestor].[dbo].[lugarespago](lugarpagoid, nombrelugarpago, empresaid)
			SELECT lugarpagoid, nombrelugarpago, empresaid FROM lugarespago WHERE lugarpagoid = @lp AND empresaid = @em
		END
	ELSE
		BEGIN 
			UPDATE [Demo_Gestor].[dbo].[lugarespago] SET 
				   [Demo_Gestor].[dbo].[lugarespago].empresaid = l.empresaid, 
				   [Demo_Gestor].[dbo].[lugarespago].nombrelugarpago = l.nombrelugarpago
			FROM [Demo_Gestor].[dbo].[lugarespago] lp
				INNER JOIN lugarespago l ON lp.lugarpagoid = l.lugarpagoid AND lp.empresaid = l.empresaid
			WHERE 
				l.lugarpagoid = @lp AND l.empresaid = @em
		END 
		*/
	
	
	--CENTRO COSTO
	BEGIN TRY			
		IF NOT EXISTS ( SELECT centrocostoid FROM [Demo_Gestor].[dbo].[centroscosto] WHERE centrocostoid = @cc AND empresaid = @em ) 
			BEGIN
				--Insertar en la tabla Personas 
				INSERT INTO [Demo_Gestor].[dbo].[centroscosto](centrocostoid, nombrecentrocosto, empresaid)
				SELECT 
					cc.centrocostoid,
					cc.nombrecentrocosto,
					cc.empresaid
				FROM centroscosto cc 
				WHERE
					cc.centrocostoid = @cc AND empresaid = @em
			END
		ELSE
			BEGIN 
				
				UPDATE [Demo_Gestor].[dbo].[centroscosto] SET 
					   [Demo_Gestor].[dbo].[centroscosto].centrocostoid = cc.centrocostoid,
					   [Demo_Gestor].[dbo].[centroscosto].nombrecentrocosto = cc.nombrecentrocosto,
					   [Demo_Gestor].[dbo].[centroscosto].empresaid = cc.empresaid  
				FROM   [Demo_Gestor].[dbo].[centroscosto] centro
					INNER JOIN centroscosto cc ON centro.centrocostoid = cc.centrocostoid  AND centro.empresaid = cc.empresaid
				WHERE 
					centro.centrocostoid = @cc and centro.empresaid = @em
			END	
	END TRY  
	BEGIN CATCH  
		SET @error		= ERROR_NUMBER()
		--SET @mensaje	= ERROR_MESSAGE()
		SET @mensaje = 'No se pudo enviar al Gestor por error con los datos del Centro de Costo'
	END CATCH 
	
	--EMPLEADOS
	BEGIN TRY
		IF NOT EXISTS ( SELECT empleadoid FROM [Demo_Gestor].[dbo].[empleados] WHERE empleadoid = @personaid )--AND empresaid = @em )
			BEGIN 
				
				INSERT INTO [Demo_Gestor].[dbo].[empleados](
					empleadoid, 
					empresaid,
					centrocostoid,
					rolid)
				SELECT 
					@personaid,
					@em,
					@cc,
					CASE rolid
						WHEN 2 THEN '0'
						WHEN 1 THEN '1'
					END AS rolid
				FROM
					Empleados
				WHERE 
					empleadoid = @personaid
			END
			
		ELSE
			BEGIN 
					
				UPDATE [Demo_Gestor].[dbo].[empleados] SET 
					   [Demo_Gestor].[dbo].[empleados].empresaid = @em,
					   [Demo_Gestor].[dbo].[empleados].centrocostoid = @cc
					   --[Demo_Gestor].[dbo].[empleados].lugarpagoid = @lp	   
				FROM  
					[Demo_Gestor].[dbo].[empleados] em
				WHERE 
					empleadoid = @personaid AND empresaid = @em
				
				SELECT 
					@em = empresaid,
					
					@cc = centrocostoid
				FROM 
					[Demo_Gestor].[dbo].[empleados]
				WHERE 
					empleadoid = @personaid
			END
	END TRY  
	BEGIN CATCH  
		SET @error		= ERROR_NUMBER()
		--SET @mensaje	= ERROR_MESSAGE()
		SET @mensaje = 'No se pudo enviar al Gestor por error con los datos del Empleado'
	END CATCH 

	--DOCUMENTO
	BEGIN TRANSACTION 
	BEGIN TRY	
		IF NOT EXISTS(SELECT  [NumeroContrato]  FROM [Demo_Gestor].[dbo].[documentosinfo] WHERE [NumeroContrato] = @piddocumento AND empleadoid = @personaid ) 
			BEGIN 
				--Insertar los datos del documento
				INSERT INTO [Demo_Gestor].[dbo].[documentosinfo](
					tipodocumentoid, 
					empleadoid, 
					empresaid, 
					centrocostoid, 
					fechadocumento, 
					fechacreacion, 
					fechatermino, 
					NumeroContrato, 
					Origen)
				SELECT 
					PL.idTipoGestor,
					@personaid,
					@em,
					@cc,
					CDV.FechaInicio,--CDV.Fecha,
					GETDATE() As FechaCreacion,
					CDV.FechaTermino,
					CDV.idDocumento,
					2 As Origen -- Rbk3
				FROM Contratos C 
				INNER JOIN ContratoDatosVariables CDV ON C.idDocumento = CDV.idDocumento
				INNER JOIN Plantillas PL ON C.idPlantilla = PL.idPlantilla
				WHERE C.idDocumento = @piddocumento
		
				SET @iddoc = @@IDENTITY
			END
		ELSE
			BEGIN 
				SELECT @error = 1
				SELECT @mensaje = 'El Documento ya fue Enviado al Gestor'
			END
		
		--Si no existe el base64 de un documento 
		IF NOT EXISTS( SELECT  [NumeroContrato]  FROM [Demo_Gestor].[dbo].[documentosinfo] DI INNER JOIN [Demo_Gestor].[dbo].[documentos] D on DI.documentoid = D.documentoid  WHERE [NumeroContrato] = @piddocumento AND empleadoid = @personaid ) 
			BEGIN
				--Inserta el documento nuevo 
				INSERT INTO [Demo_Gestor].[dbo].[documentos](documentoid, documento, nombrearchivo, tipoconversion)
				SELECT 
					@iddoc,
					documento, 
					NombreArchivo + '.' + Extension,
					1 --B64 de Origen 5(Rbk3)
				FROM 
					Documentos
				WHERE 
					idDocumento = @piddocumento
					
				--Modificar el estado de enviado al Gestor 
				UPDATE Contratos SET Enviado = 1 WHERE idDocumento = @piddocumento
			END
		ELSE
			BEGIN 
				--Consulta el id del documento 
				SELECT @iddoc = DI.documentoid  FROM [Demo_Gestor].[dbo].[documentosinfo] DI WHERE [NumeroContrato] = @piddocumento AND empleadoid = @personaid
				
				--Actualiza los datos del documento 
				UPDATE [Demo_Gestor].[dbo].[documentos] SET 
					   [Demo_Gestor].[dbo].[documentos].documento = D.documento,
					   [Demo_Gestor].[dbo].[documentos].nombrearchivo = D.NombreArchivo + '.' + D.Extension, 
					   [Demo_Gestor].[dbo].[documentos].tipoconversion = 1 --B64 de Origen 5(Rbk3)
				FROM   [Demo_Gestor].[dbo].[documentos] documentos
					INNER JOIN Documentos D ON documentos.documentoid = D.idDocumento
				WHERE 
					documentos.documentoid = @iddoc
				
				--Modificar el estado de enviado al Gestor 
				UPDATE Contratos SET Enviado = 1 WHERE idDocumento = @piddocumento				
			END 
			
	COMMIT TRANSACTION
	END TRY

	BEGIN CATCH
	ROLLBACK TRANSACTION 
	
		SET @error		= ERROR_NUMBER()
		SET @mensaje	= ERROR_MESSAGE()
	
	END CATCH
	
	SELECT @error as error, @mensaje as mensaje
	RETURN
END
GO
