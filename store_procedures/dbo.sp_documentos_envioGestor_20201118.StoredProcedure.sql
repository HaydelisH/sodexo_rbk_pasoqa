USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_documentos_envioGestor_20201118]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez 
-- Creado el: 115-04-2019
-- Descripcion:  Enviar datos del documento al Gestor 
-- Ejemplo: sp_documentos_envioGestor 3471
-- =============================================
CREATE PROCEDURE [dbo].[sp_documentos_envioGestor_20201118]
	@piddocumento INT,
	@debug			tinyint	= 0	
AS
BEGIN	
	SET NOCOUNT ON;	
	
	DECLARE @iddoc INT
	DECLARE @personaid NVARCHAR(10)
	DECLARE @cc	NVARCHAR(14)
	DECLARE @em NVARCHAR(10)
	DECLARE @lp NVARCHAR(14)
	DECLARE @error		INT
	DECLARE @mensaje	VARCHAR(200)
	DECLARE @sqlString NVARCHAR(max)
	DECLARE @resultado INT
	DECLARE @Param nvarchar(max)
	DECLARE @base_gestor		VARCHAR(120);	

	SET @iddoc = 0
	
	SELECT @base_gestor = parametro FROM Parametros WHERE idparametro = 'gestor'	
  	
  	--REVISAR Y COLOCAR NOMBRES IGUALES EN CENTROS DE COSTO Y LUGARES DE PAGO

	--Consultamos los datos necesarios 
	SELECT 
		@personaid = CDV.Rut,  --Persona 
		@cc = CDV.CentroCosto, --Centro de costo 
		@em = C.RutEmpresa,    --Empresa
		@lp = CDV.lugarpagoid  --Lugar de pago
	FROM Contratos C
	INNER JOIN ContratoDatosVariables CDV ON C.idDocumento = CDV.idDocumento
	WHERE C.idDocumento = @piddocumento

	IF (@debug = 1)
			BEGIN
				PRINT @personaid  --Persona 
				PRINT @cc --Centro de costo 
				PRINT @em   --Empresa
				PRINT @lp --Lugar de pago
			END 

	--Si falta algun dato, nos vamos 
	IF ( @personaid = '' OR @cc = '' OR @em = '' OR @lp = '' )
		BEGIN 
			SELECT @error = 1
			SELECT @mensaje = 'No se pudo enviar al Gestor porque faltan datos'
			SELECT @error as error , @mensaje as mensaje 
			RETURN
		END
	
	--PERSONA
	BEGIN TRY
	
		SET @resultado = 0
		SET @sqlString = N'SELECT @resultado = COUNT(*) FROM [' + @base_gestor + '].[dbo].[personas] WHERE personaid = ''' + @personaid + ''''
		SET @Param = N'@resultado INT OUTPUT'
		
		IF (@debug = 1)
				BEGIN
					PRINT @sqlString
				END

		EXECUTE sp_executesql @sqlString,  @Param, @resultado = @resultado OUTPUT;
	
		IF ( @resultado = 0 )
			BEGIN 
						
				SET @sqlString = N'
				INSERT INTO [' + @base_gestor + '].[dbo].[personas](personaid, nombre, appaterno, apmaterno, nacionalidad, estadocivil, fechanacimiento, direccion, comuna, ciudad, correo)
					SELECT 
						P.personaid, 
						P.nombre,
						P.appaterno,
						P.apmaterno,
						P.nacionalidad,
					   CASE 
							WHEN p.estadocivil = 1 THEN ''Soltero(a)''
							WHEN p.estadocivil = 2 THEN ''Casado(a)''
							WHEN p.estadocivil = 3 THEN ''Divorciado(a)''
							WHEN p.estadocivil = 4 THEN ''Viudo(a)''
						END As estadocivil,
						P.fechanacimiento ,
						P.direccion,
						P.comuna,
						P.ciudad,
						P.correo
					FROM personas P

					WHERE
						P.personaid =  ''' + @personaid + ''''
				 EXECUTE sp_executesql @sqlString;

				IF (@debug = 1)
				BEGIN
					PRINT @sqlString
				END
			END 
		ELSE
			BEGIN 
				SET @sqlString = N'
					UPDATE [' + @base_gestor + '].[dbo].[personas] SET 
					   [' + @base_gestor + '].[dbo].[personas].personaid = p.personaid,
					   [' + @base_gestor + '].[dbo].[personas].nombre = p.nombre,
					   [' + @base_gestor + '].[dbo].[personas].appaterno = p.appaterno,
					   [' + @base_gestor + '].[dbo].[personas].apmaterno = p.apmaterno,
					   [' + @base_gestor + '].[dbo].[personas].nacionalidad = p.nacionalidad,
					   [' + @base_gestor + '].[dbo].[personas].estadocivil = 
					   CASE 
							WHEN p.estadocivil = 1 THEN ''Soltero(a)''
							WHEN P.estadocivil = 2 THEN ''Casado(a)''
							WHEN p.estadocivil = 3 THEN ''Divorciado(a)''
							WHEN p.estadocivil = 4 THEN ''Viudo(a)''
						END,
						[' + @base_gestor + '].[dbo].[personas].fechanacimiento = p.fechanacimiento,
						[' + @base_gestor + '].[dbo].[personas].direccion = p.direccion,
						[' + @base_gestor + '].[dbo].[personas].comuna= p.comuna,
						[' + @base_gestor + '].[dbo].[personas].ciudad = p.ciudad,
						[' + @base_gestor + '].[dbo].[personas].correo = p.correo
					   
				FROM [' + @base_gestor + '].[dbo].[personas] per
					INNER JOIN personas p  ON per.personaid = p.personaid
				WHERE 
					p.personaid = ''' + @personaid + ''''
				 EXECUTE sp_executesql @sqlString;
				
				IF (@debug = 1)
				BEGIN
					PRINT @sqlString
				END
			END
			
	END TRY  
	BEGIN CATCH  
		SET @error		= ERROR_NUMBER()
		--SET @mensaje	= ERROR_MESSAGE()
		SET @mensaje = 'No se pudo enviar al Gestor por error con los datos personales del Empleado'
		SELECT @error as error, @mensaje as mensaje
		RETURN
	END CATCH 

	--EMPRESA
	BEGIN TRY	
	
		SET @resultado = 0
		SET @sqlString = N'SELECT @resultado = COUNT(*) FROM [' + @base_gestor + '].[dbo].[empresas] WHERE empresaid = ''' + @em + ''''
		SET @Param = N'@resultado INT OUTPUT'

		IF (@debug = 1)
				BEGIN
					PRINT @sqlString
				END

		EXECUTE sp_executesql @sqlString,  @Param, @resultado = @resultado OUTPUT;
	
		IF ( @resultado = 0 )
			BEGIN
				SET @sqlString = N'
				INSERT INTO [' + @base_gestor + '].[dbo].[empresas](
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
					RutEmpresa = ''' + @em + ''''
					
				 EXECUTE sp_executesql @sqlString;

				IF (@debug = 1)
				BEGIN
					PRINT @sqlString
				END
			END
		ELSE
			BEGIN 				
				 SET @sqlString = N'
				 UPDATE [' + @base_gestor + '].[dbo].[empresas] SET 
					[' + @base_gestor + '].[dbo].[empresas].nombre = E.RazonSocial,
					[' + @base_gestor + '].[dbo].[empresas].direccion = E.Direccion,
					[' + @base_gestor + '].[dbo].[empresas].comuna = E.Comuna,
					[' + @base_gestor + '].[dbo].[empresas].ciudad = E.Ciudad
				  FROM [' + @base_gestor + '].[dbo].[empresas] em
				  INNER JOIN Empresas E ON em.empresaid = E.RutEmpresa
				  WHERE 
					E.RutEmpresa = ''' + @em + ''''
					
				  EXECUTE sp_executesql @sqlString;

				IF (@debug = 1)
				BEGIN
					PRINT @sqlString
				END
			END
	END TRY  
	BEGIN CATCH  
		SET @error		= ERROR_NUMBER()
		--SET @mensaje	= ERROR_MESSAGE()
		SET @mensaje = 'No se pudo enviar al Gestor por error con los datos de Empresa'
		SELECT @error as error, @mensaje as mensaje
		RETURN
	END CATCH 
	
	--LUGAR DE PAGO
	BEGIN TRY	
	
		SET @resultado = 0
		SET @sqlString = N'SELECT @resultado = COUNT(*) FROM [' + @base_gestor + '].[dbo].[lugarespago] WHERE lugarpagoid = ''' + @lp + ''' AND empresaid = ''' + @em + ''''
		SET @Param = N'@resultado INT OUTPUT'

		IF (@debug = 1)
			BEGIN
				PRINT @sqlString
			END

		EXECUTE sp_executesql @sqlString,  @Param, @resultado = @resultado OUTPUT;
	
		IF ( @resultado = 0 )
			BEGIN
				 SET @sqlString = N'
					INSERT INTO [' + @base_gestor + '].[dbo].[lugarespago](lugarpagoid, nombrelugarpago, empresaid)
					SELECT lugarpagoid, nombrelugarpago, empresaid FROM lugarespago WHERE lugarpagoid =''' + @lp + ''' AND empresaid = ''' + @em + ''
			  
				EXECUTE sp_executesql @sqlString;

				IF (@debug = 1)
				BEGIN
					PRINT @sqlString
				END
			END
		
		ELSE
			BEGIN 
			 SET @sqlString = N'
			 UPDATE [' + @base_gestor + '].[dbo].[lugarespago] SET 
				   [' + @base_gestor + '].[dbo].[lugarespago].empresaid = l.empresaid, 
				   [' + @base_gestor + '].[dbo].[lugarespago].nombrelugarpago = l.nombrelugarpago
			FROM [' + @base_gestor + '].[dbo].[lugarespago] lp
				INNER JOIN lugarespago l ON lp.lugarpagoid = l.lugarpagoid AND lp.empresaid = l.empresaid
			WHERE 
				l.lugarpagoid = '''+ @lp + ''' AND l.empresaid = ''' + @em + ''''
 		
			EXECUTE sp_executesql @sqlString;

				IF (@debug = 1)
				BEGIN
					PRINT @sqlString
				END
			END
	END TRY  
	BEGIN CATCH  
		SET @error		= ERROR_NUMBER()
		--SET @mensaje	= ERROR_MESSAGE()
		SET @mensaje = 'No se pudo enviar al Gestor por error con los datos del Lugar de Pago'
		SELECT @error as error, @mensaje as mensaje
		RETURN
	END CATCH 
	
	--CENTRO COSTO
	BEGIN TRY			
	
		SET @resultado = 0
		SET @sqlString = N'SELECT @resultado = COUNT(*) FROM [' + @base_gestor + '].[dbo].[centroscosto] WHERE centrocostoid = ''' + @cc + ''' AND lugarpagoid = ''' + @lp  + '''' + ' AND empresaid = ''' + @em  + ''''
		SET @Param = N'@resultado INT OUTPUT'

		IF (@debug = 1)
			BEGIN
				PRINT @sqlString
			END

		EXECUTE sp_executesql @sqlString,  @Param, @resultado = @resultado OUTPUT;
		
		IF ( @resultado = 0 )
			BEGIN
				--Insertar en la tabla Personas 
				SET @sqlString = N'
				INSERT INTO [' + @base_gestor + '].[dbo].[centroscosto](centrocostoid, nombrecentrocosto, lugarpagoid, empresaid)
				SELECT 
					cc.centrocostoid,
					cc.nombrecentrocosto,
					cc.lugarpagoid,
					cc.empresaid
				FROM centroscosto cc 
				WHERE
					cc.centrocostoid = ''' + @cc + ''' AND cc.empresaid = ''' + @em + ''' AND cc.lugarpagoid = ''' + @lp + ''''
					--cc.centrocostoid = ''' + @cc + ''' AND cc.lugarpagoid = ''' + @lp + ''''
				EXECUTE sp_executesql @sqlString;

				IF (@debug = 1)
				BEGIN
					PRINT @sqlString
				END
			END
		ELSE
			BEGIN 
				SET @sqlString = N'
				UPDATE [' + @base_gestor + '].[dbo].[centroscosto] SET 
					   [' + @base_gestor + '].[dbo].[centroscosto].centrocostoid = cc.centrocostoid,
					   [' + @base_gestor + '].[dbo].[centroscosto].nombrecentrocosto = cc.nombrecentrocosto,
					   [' + @base_gestor + '].[dbo].[centroscosto].lugarpagoid = cc.lugarpagoid  
				FROM   [' + @base_gestor + '].[dbo].[centroscosto] centro
					INNER JOIN centroscosto cc ON centro.centrocostoid = cc.centrocostoid  AND centro.lugarpagoid = cc.lugarpagoid AND centro.empresaid = cc.empresaid
				WHERE 
					centro.centrocostoid = ''' + @cc + ''' and centro.empresaid = ''' + @em + ''' AND centro.lugarpagoid = ''' + @lp + ''''
					--centro.centrocostoid = ''' + @cc + ''' and centro.lugarpagoid = ''' + @lp + ''''
				EXECUTE sp_executesql @sqlString;

				IF (@debug = 1)
				BEGIN
					PRINT @sqlString
				END
			END	
	END TRY  
	BEGIN CATCH  
		SET @error		= ERROR_NUMBER()
		--SET @mensaje	= ERROR_MESSAGE()
		SET @mensaje = 'No se pudo enviar al Gestor por error con los datos del Centro de Costo'
		SELECT @error as error, @mensaje as mensaje
		RETURN
	END CATCH 
	
	--EMPLEADOS
	BEGIN TRY
	
		SET @resultado = 0
		SET @sqlString = N'SELECT @resultado = COUNT(*) FROM [' + @base_gestor + '].[dbo].[empleados] WHERE empleadoid = ''' + @personaid + '''' --AND empresaid = @em 
		SET @Param = N'@resultado INT OUTPUT'
				
		EXECUTE sp_executesql @sqlString,  @Param, @resultado = @resultado OUTPUT;
		
		IF (@debug = 1)
			BEGIN
				PRINT @sqlString
			END

		IF ( @resultado = 0 )
			BEGIN 
				SET @sqlString = N'
				INSERT INTO [' + @base_gestor + '].[dbo].[empleados](
					empleadoid, 
					empresaid,
					centrocostoid,
					LugarPago,
					rolid)
				SELECT 
					''' + @personaid + ''',
					''' + @em + ''',
					''' + @cc + ''',
					''' + @lp + ''',
					CASE rolid
						WHEN 2 THEN ''0''
						WHEN 1 THEN ''1''
					END AS rolid
				FROM
					Empleados
				WHERE 
					empleadoid = ''' + @personaid + ''''
				
				EXECUTE sp_executesql @sqlString;

				IF (@debug = 1)
				BEGIN
					PRINT @sqlString
				END
			END
			
		ELSE
			BEGIN 
				--SET @sqlString = N'					
				--UPDATE [' + @base_gestor + '].[dbo].[empleados] SET 
				--	   [' + @base_gestor + '].[dbo].[empleados].empresaid = ''' + @em + ''',
				--	   [' + @base_gestor + '].[dbo].[empleados].centrocostoid = ''' + @cc + '''
				--	 --  [' + @base_gestor + '].[dbo].[empleados].lugarpagoid = ''' + @lp + '''
				--FROM  
				--	[' + @base_gestor + '].[dbo].[empleados] em
				--WHERE 
				--	empleadoid = ''' + @personaid + ''''
					
				--EXECUTE sp_executesql @sqlString;

				--IF (@debug = 1)
				--BEGIN
				--	PRINT @sqlString
				--END
		
				SET @sqlString = N'	
				SELECT 
					@em = empresaid,
					@cc = centrocostoid,
					@lp = LugarPago
				FROM 
					[' + @base_gestor + '].[dbo].[empleados]
				WHERE 
					empleadoid = ''' + @personaid + ''''
				SET @Param = N' @em VARCHAR(10) OUTPUT, @cc NVARCHAR(14) OUTPUT, @lp NVARCHAR(14) OUTPUT'
			
				EXECUTE sp_executesql @sqlString,  @Param, @em = @em OUTPUT, @cc = @cc OUTPUT,  @lp = @lp OUTPUT

				IF (@debug = 1)
				BEGIN
					PRINT @sqlString
				END
			END
	END TRY  
	BEGIN CATCH  
		SET @error		= ERROR_NUMBER()
		--SET @mensaje	= ERROR_MESSAGE()
		SET @mensaje = 'No se pudo enviar al Gestor por error con los datos del Empleado'
		SELECT @error as error, @mensaje as mensaje
		RETURN
	END CATCH 

	--DOCUMENTO
	BEGIN TRANSACTION 
	BEGIN TRY	
	
		SET @resultado = 0
		SET @sqlString = N'SELECT @resultado = COUNT(*) FROM [' + @base_gestor + '].[dbo].[documentosinfo] WHERE [NumeroContrato] = ' + CONVERT(VARCHAR(20), @piddocumento) + ' AND empleadoid = ''' + @personaid + ''''
		SET @Param = N' @resultado INT OUTPUT'

		IF (@debug = 1)
		BEGIN
			PRINT @sqlString
		END

		EXECUTE sp_executesql @sqlString,  @Param, @resultado = @resultado OUTPUT
		
		IF ( @resultado = 0 )
			BEGIN 
				--Insertar los datos del documento
				SET @sqlString = N'
				INSERT INTO [' + @base_gestor + '].[dbo].[documentosinfo](
					tipodocumentoid, 
					empleadoid, 
					empresaid, 
					centrocostoid,
					lugarpagoid,
					fechadocumento, 
					fechacreacion, 
					fechatermino, 
					NumeroContrato, 
					Origen)
				SELECT 
					PL.idTipoGestor,
					CDV.Rut,
					''' + @em + ''',
					''' + @cc + ''',
					''' + @lp + ''',
					--CDV.lugarpagoid,
					CASE 
						WHEN CDV.fechadocumento  IS NULL THEN CDV.FechaInicio
						ELSE CDV.fechadocumento
					END,--CDV.FechaInicio,--CDV.Fecha,
					GETDATE() As FechaCreacion,
					CDV.FechaTermino,
					CDV.idDocumento,
					2 As Origen -- Rbk3
				FROM Contratos C 
				INNER JOIN ContratoDatosVariables CDV ON C.idDocumento = CDV.idDocumento
				INNER JOIN Plantillas PL ON C.idPlantilla = PL.idPlantilla
				WHERE C.idDocumento = ' + CONVERT(VARCHAR(20), @piddocumento)
					
				IF (@debug = 1)
				BEGIN
					PRINT @sqlString
				END

				EXECUTE sp_executesql @sqlString;
		
				--SET @iddoc = @@IDENTITY
				SET @iddoc = SCOPE_IDENTITY()

				IF (@debug = 1)
				BEGIN
					PRINT @sqlString
					PRINT @iddoc 
				END
			END
		ELSE
			BEGIN 
				SELECT @error = 1
				SELECT @mensaje = 'El Documento ya fue Enviado al Gestor'
			END
		
		SET @resultado = 0
		SET @sqlString = N'SELECT @resultado = COUNT(*) FROM [' + @base_gestor + '].[dbo].[documentosinfo] DI INNER JOIN [' + @base_gestor + '].[dbo].[documentos] D on DI.documentoid = D.documentoid  WHERE [NumeroContrato] = ' + CONVERT(VARCHAR(20), @piddocumento) + ' AND empleadoid = ''' + @personaid + ''''
		SET @Param = N'@resultado INT OUTPUT'

		EXECUTE sp_executesql @sqlString,  @Param, @resultado = @resultado OUTPUT;
		
		IF (@debug = 1)
		BEGIN
			PRINT @sqlString
		END
			
		--Si no existe el base64 de un documento 
		IF ( @resultado = 0 )
			BEGIN
				
				IF( @iddoc = '' )
					BEGIN
						--Consulta el id del documento
						SET @resultado = 0
						SET @sqlString = N'SELECT @iddoc = DI.documentoid  FROM [' + @base_gestor + '].[dbo].[documentosinfo] DI WHERE [NumeroContrato] = ' + CONVERT(VARCHAR(20), @piddocumento) + '  AND empleadoid = ''' + @personaid + ''''
						SET @Param = N'@iddoc INT OUTPUT'

						IF (@debug = 1)
						BEGIN
							PRINT @sqlString
						END

						EXECUTE sp_executesql @sqlString,  @Param, @iddoc = @iddoc OUTPUT;
					END 
				 
				--Inserta el documento nuevo 
				SET @sqlString = N'
				INSERT INTO [' + @base_gestor + '].[dbo].[documentos](documentoid, documento, nombrearchivo, tipoconversion)
				SELECT 
					' + CONVERT(VARCHAR(20), @iddoc) + ',
					documento, 
					NombreArchivo + ''.'' + Extension,
					1 --B64 de Origen 5(Rbk3)
				FROM 
					Documentos
				WHERE 
					idDocumento = ' + CONVERT(VARCHAR(20), @piddocumento) 
			
				EXECUTE sp_executesql @sqlString;

				IF (@debug = 1)
				BEGIN
					PRINT @sqlString
				END
					
				--Modificar el estado de enviado al Gestor 
				SET @sqlString = N' UPDATE Contratos SET Enviado = 1 WHERE idDocumento = ' + CONVERT(VARCHAR(20), @piddocumento) 
				
				EXECUTE sp_executesql @sqlString;

				IF (@debug = 1)
				BEGIN
					PRINT @sqlString
				END
			END
		ELSE
			BEGIN 
				--Consulta el id del documento
				SET @resultado = 0
				SET @sqlString = N'SELECT @iddoc = DI.documentoid  FROM [' + @base_gestor + '].[dbo].[documentosinfo] DI WHERE [NumeroContrato] = ' + CONVERT(VARCHAR(20), @piddocumento) + '  AND empleadoid = ''' + @personaid + ''''
				SET @Param = N'@iddoc INT OUTPUT'

				EXECUTE sp_executesql @sqlString,  @Param, @iddoc = @iddoc OUTPUT;
			
				--Actualiza los datos del documento 
				SET @sqlString = N'
				UPDATE [' + @base_gestor + '].[dbo].[documentos] SET 
					   [' + @base_gestor + '].[dbo].[documentos].documento = D.documento,
					   [' + @base_gestor + '].[dbo].[documentos].nombrearchivo = D.NombreArchivo + ''.'' + D.Extension, 
					   [' + @base_gestor + '].[dbo].[documentos].tipoconversion = 1 --B64 de Origen 5(Rbk3)
				FROM   [' + @base_gestor + '].[dbo].[documentos] documentos
					INNER JOIN Documentos D ON documentos.documentoid = D.idDocumento
				WHERE 
					documentos.documentoid = ' + CONVERT(VARCHAR(20), @iddoc) 
				
			    EXECUTE sp_executesql @sqlString;
				
				IF (@debug = 1)
				BEGIN
					PRINT @sqlString
				END

				--Modificar el estado de enviado al Gestor 
				SET @sqlString = N'
				UPDATE Contratos SET Enviado = 1 WHERE idDocumento = ' + CONVERT(VARCHAR(20), @piddocumento) 
				
				 EXECUTE sp_executesql @sqlString;		
				 
				 IF (@debug = 1)
				 BEGIN
					PRINT @sqlString
				END
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
