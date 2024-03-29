USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_fichas_agregar]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- Ejemplo:exec sp_fichas_agregar 
-- =============================================
CREATE PROCEDURE [dbo].[sp_fichas_agregar]
	@empresaid			VARCHAR(10),
	@centrocostoid		NVARCHAR(14),
	--@lugarpagoid		NVARCHAR(14),
	@empleadoid			VARCHAR(10), 
	@nacionalidad		VARCHAR(20),
	@nombre			    VARCHAR(110),
	@correo				VARCHAR(100),
	@direccion			VARCHAR(100),
	@ciudad				VARCHAR(50),
	@comuna				VARCHAR(50),
	@fechanacimiento	DATE,
	@estadocivil		INT,
	@rolid				INT,
	@fono				VARCHAR(20),
	@tipodocumentoid	INT,
	@documento			VARCHAR(MAX),
	@nombrearchivo      VARCHAR(100),
	@clave				NVARCHAR(100),
	@idFirma			INT
	
AS	
BEGIN
	SET NOCOUNT ON;
	
	DECLARE @error		INT
	DECLARE @mensaje	VARCHAR(200)	
	DECLARE @iddoc INT 
	DECLARE @idfic INT 
	DECLARE @archivo VARBINARY(MAX)	
	DECLARE @envios INT
	DECLARE @xencoded		VARCHAR(MAX)
	DECLARE @cc	NVARCHAR(14)
	DECLARE @em VARCHAR(10)
	DECLARE @lp NVARCHAR(14)
	DECLARE @band INT;

	SET @xencoded = CAST('' AS XML).value('xs:base64Binary(sql:variable(''@documento''))', 'varbinary(max)')		
	
	IF @xencoded IS NULL
	BEGIN
		SET @error = 1 
		SET @mensaje = ' Archivo PDF Mal Codificado'
		SELECT @error AS error, @mensaje AS mensaje 
		RETURN
	END
	
	SELECT @archivo= CONVERT(varbinary(max), @documento)
	
	--Validar si existe persona en RBK
	IF NOT EXISTS ( SELECT personaid FROM personas WHERE personaid = @empleadoid ) 
		BEGIN
			--Insertar en la tabla Personas 
			INSERT INTO personas(personaid, nombre,nacionalidad, estadocivil, fechanacimiento,direccion, comuna, ciudad, correo, fono, Eliminado)
				  VALUES(@empleadoid, @nombre,@nacionalidad, @estadocivil, @fechanacimiento, @direccion, @comuna, @ciudad,@correo,@fono, 0)
		END
	ELSE
		BEGIN 
			--Actualiza los datos
			UPDATE personas SET
				nombre = @nombre,
				nacionalidad = @nacionalidad,
				estadocivil = @estadocivil,
				fechanacimiento = @fechanacimiento,
				direccion = @direccion,
				comuna = @comuna,
				ciudad = @ciudad,
				correo = @correo,
				fono = @fono,
				Eliminado = 0
			WHERE personaid = @empleadoid
		END
	
	--Validar si existe persona en Gestor 
	IF NOT EXISTS ( SELECT personaid FROM  [Smu_Gestor].[dbo].[personas] WHERE personaid = @empleadoid )
		BEGIN 
			INSERT INTO  [Smu_Gestor].[dbo].[personas](personaid, nombre, nacionalidad, estadocivil, fechanacimiento, direccion, comuna, ciudad, correo, fono)
			VALUES(@empleadoid, @nombre, @nacionalidad,
				--Validar Estado Civil
				CASE 
					WHEN @estadocivil = 1 THEN 'Soltero(a)'
					WHEN @estadocivil = 2 THEN 'Casado(a)'
					WHEN @estadocivil = 3 THEN 'Divorciado(a)'
					WHEN @estadocivil = 4 THEN 'Viudo(a)'
				END,
				@fechanacimiento, @direccion, @comuna, @ciudad, @correo, @fono)
		END 
	ELSE
		BEGIN
			--Actualiza los datos
			UPDATE [Smu_Gestor].[dbo].[personas]  SET
				nombre = @nombre,
				nacionalidad = @nacionalidad,
				estadocivil = 	
				--Validar Estado Civil
				CASE 
					WHEN @estadocivil = 1 THEN 'Soltero(a)'
					WHEN @estadocivil = 2 THEN 'Casado(a)'
					WHEN @estadocivil = 3 THEN 'Divorciado(a)'
					WHEN @estadocivil = 4 THEN 'Viudo(a)'
				END,
				fechanacimiento = @fechanacimiento,
				direccion = @direccion,
				comuna = @comuna,
				ciudad = @ciudad,
				correo = @correo,
				fono = @fono
			WHERE personaid = @empleadoid
		END 
			
	--Crear como usuario
	IF NOT EXISTS ( SELECT usuarioid FROM usuarios WHERE usuarioid = @empleadoid ) 
		BEGIN
		INSERT INTO usuarios
				(usuarioid,clave,estado,bloqueado,cambiarclave,tipousuarioid, fechacreacion, idFirma)
				VALUES
				(@empleadoid,@clave,1,0,1,5,GETDATE(),@idFirma)
		END
		
	--Validamos de que exista empresa
	IF NOT EXISTS ( SELECT empresaid FROM [Smu_Gestor].[dbo].[empresas] WHERE empresaid = @empresaid ) 
		BEGIN 
			INSERT INTO [Smu_Gestor].[dbo].[empresas] (empresaid, nombre, direccion, comuna, ciudad)
			SELECT  RutEmpresa, RazonSocial, Direccion, Comuna, Ciudad FROM Empresas WHERE RutEmpresa = @empresaid
		END
	ELSE
		BEGIN 
			 UPDATE [Smu_Gestor].[dbo].[empresas] SET 
				[Smu_Gestor].[dbo].[empresas].nombre = E.RazonSocial,
				[Smu_Gestor].[dbo].[empresas].direccion = E.Direccion,
				[Smu_Gestor].[dbo].[empresas].comuna = E.Comuna,
				[Smu_Gestor].[dbo].[empresas].ciudad = E.Ciudad
			  FROM [Smu_Gestor].[dbo].[empresas] em
			  INNER JOIN Empresas E ON em.empresaid = E.RutEmpresa
			  WHERE 
				em.empresaid = @empresaid
		END 
	
	--Validamos si existe el lugar de pago 
	/*IF NOT EXISTS( SELECT * FROM [Smu_Gestor].[dbo].[lugarespago] WHERE lugarpagoid = @lugarpagoid AND empresaid = @empresaid ) 
		BEGIN
			INSERT INTO [Smu_Gestor].[dbo].[lugarespago](lugarpagoid, nombrelugarpago, empresaid)
			SELECT lugarpagoid, nombrelugarpago, empresaid FROM lugarespago WHERE lugarpagoid = @lugarpagoid
		END
	ELSE
		BEGIN 
			UPDATE [Smu_Gestor].[dbo].[lugarespago] SET 
				   [Smu_Gestor].[dbo].[lugarespago].empresaid = l.empresaid, 
				   [Smu_Gestor].[dbo].[lugarespago].nombrelugarpago = l.nombrelugarpago
			FROM [Smu_Gestor].[dbo].[lugarespago] lp
				INNER JOIN lugarespago l ON lp.lugarpagoid = l.lugarpagoid 
			WHERE 
				lp.lugarpagoid = @lugarpagoid AND lp.empresaid = @empresaid
		END */
	
	--Validamos si existe el centro de costo 
	IF NOT EXISTS( SELECT centrocostoid FROM [Smu_Gestor].[dbo].[centroscosto] WHERE centrocostoid = @centrocostoid AND empresaid = @empresaid ) 
		BEGIN
			INSERT INTO [Smu_Gestor].[dbo].[centroscosto](centrocostoid, nombrecentrocosto, empresaid)
			SELECT centrocostoid, nombrecentrocosto, empresaid FROM centroscosto WHERE centrocostoid = @centrocostoid AND empresaid = @empresaid
		END
	ELSE
		BEGIN 
			UPDATE [Smu_Gestor].[dbo].[centroscosto] SET  
				   [Smu_Gestor].[dbo].[centroscosto].nombrecentrocosto = cc.nombrecentrocosto
			FROM [Smu_Gestor].[dbo].[centroscosto] c
				INNER JOIN centroscosto cc ON c.centrocostoid = cc.centrocostoid 
			WHERE 
				c.centrocostoid = @centrocostoid AND c.empresaid = @empresaid 
		END 
		
	--Validar empleado 
	SET @band = 0 
	IF NOT EXISTS ( SELECT empleadoid FROM [Smu_Gestor].[dbo].[empleados] WHERE empleadoid = @empleadoid )--AND empresaid = @empresaid)
		BEGIN 
			
			INSERT INTO [Smu_Gestor].[dbo].[empleados](
				empleadoid, 
				empresaid,
				centrocostoid,
				rolid,
				estado,
				fechaingreso,
				fechatermino,
				codigoempleado)
			VALUES(@empleadoid, @empresaid, @centrocostoid, 
				CASE @rolid
					WHEN 2 THEN '0' --PUBLICO
					WHEN 1 THEN '1' --PRIVADO
				END,0 --0 VIGENTE, 1 FINIQUITADO 
				,NULL,NULL, NULL)	
		END
	ELSE
		BEGIN 
			--UPDATE  [Smu_Gestor].[dbo].[empleados] SET 
			--	empresaid = @empleadoid,
			--	centrocostoid = @centrocostoid,
			--	lugarpagoid = @lugarpagoid,
			--	rolid = CASE @rolid
			--		WHEN 2 THEN '0'
			--		WHEN 1 THEN '1'
			--	END
			--WHERE 
			--	empleadoid = @empleadoid AND empresaid = @empresaid	
			
			SELECT 
				@em = empresaid,
				@cc = centrocostoid
			FROM 
				[Smu_Gestor].[dbo].[empleados]
			WHERE 
				empleadoid = @empleadoid
				
			SET @band = 1
		END 
	
	
	BEGIN TRANSACTION 
	BEGIN TRY			
		--Insertar en la tabla de fichas
		INSERT INTO fichas(empleadoid, empresaid, centrocostoid, fechasolicitud, estadoid) 
					VALUES(@empleadoid, @empresaid, @centrocostoid, GETDATE(), 1)
		
		--Asignamos valor a variable
		SET @idfic = @@IDENTITY
		
		IF ( @band = 0 ) 
			BEGIN 
				--Insertar en la tabla de DocumentosInfo
				INSERT INTO [Smu_Gestor].[dbo].[documentosinfo]( tipodocumentoid, empleadoid, empresaid, centrocostoid, fechadocumento, fechacreacion, fechatermino, NumeroContrato, Origen, idDocEnviado, documentoidorig, validacionpaginaid, usuarioid, validacionid, codigoempleado)
							VALUES( @tipodocumentoid,@empleadoid, @empresaid, @centrocostoid, NULL, GETDATE(),NULL,0,0,0,NULL,0,@empleadoid,0, NULL)
			END
		ELSE
			BEGIN
				--Insertar en la tabla de DocumentosInfo
				INSERT INTO [Smu_Gestor].[dbo].[documentosinfo]( tipodocumentoid, empleadoid, empresaid, centrocostoid, fechadocumento, fechacreacion, fechatermino, NumeroContrato, Origen, idDocEnviado, lugarpagoid1, documentoidorig, validacionpaginaid, usuarioid, validacionid, codigoempleado)
							VALUES( @tipodocumentoid,@empleadoid, @em, @cc, NULL, GETDATE(),NULL,0,0,0,@lp,NULL,0,@empleadoid,0, NULL)
	
			END 
			
		SET @iddoc = @@IDENTITY
		
		--Guardar archivo de subida		    
		INSERT INTO [Smu_Gestor].[dbo].[documentos](documentoid,documento,nombrearchivo,tipoconversion)
		VALUES(@iddoc,@archivo,@nombrearchivo,1)
					    
					    
		--Insertar relacion
		INSERT INTO fichasdocumentos (fichaid, documentoid)
							   VALUES(@idfic, @iddoc )
							  				   
	COMMIT TRANSACTION
	END TRY

	BEGIN CATCH
	ROLLBACK TRANSACTION 
		
		SET @error		= ERROR_NUMBER()
		SET @mensaje	= ERROR_MESSAGE()
			
	END CATCH
	
	SELECT @iddoc As documentoid, @idfic As fichaid, @error AS error, @mensaje AS mensaje; 
	
	RETURN;
END
GO
