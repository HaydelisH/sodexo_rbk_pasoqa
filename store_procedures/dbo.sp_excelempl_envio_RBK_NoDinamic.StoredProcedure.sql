USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_excelempl_envio_RBK_NoDinamic]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Macarena Parra Bruna
-- Creado el: 13/06/2018
-- Descripcion: Agregar Empresas
--	SELECT * FROM EXCELEMPL
--	SELECT * FROM personas
--	SELECT * FROM QA_Synthon_Gestor.dbo.personas
-- Ejemplo:exec sp_excelempl_envio_Gestor_NoDinamic '12382466-0',1 
-- =============================================
CREATE PROCEDURE [dbo].[sp_excelempl_envio_RBK_NoDinamic]
	@pusuarioid NVARCHAR(14),	
	@debug			tinyint	=0		-- DEBUG 1= imprime consulta
	
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;
	
	DECLARE @lmensaje	VARCHAR(200)
	DECLARE @error		INT
	DECLARE @total		INT;
	DECLARE @eliminado  BIT;
	DECLARE @basex			VARCHAR(20)
	DECLARE @base_gestor		VARCHAR(20)
	DECLARE @actualizar		VARCHAR(20)
	DECLARE @sqlString nvarchar(max)
	DECLARE @Param nvarchar(max)
	DECLARE @nl				char(2) = char(13) + char(10)
	DECLARE @totalreg DECIMAL (9,2);
	
	declare @MsjE nvarchar(MAX) 
	DECLARE @Cadena VARCHAR(MAX)
	
	--SELECT @base_gestor = descripcion FROM Sodexo_Gestor.dbo.Parametros WHERE parametroid = 12
	SELECT @actualizar = valor  FROM Sodexo_Gestor.dbo.Parametros WHERE parametroid  = 38

	IF(@actualizar =1)
	BEGIN


	------------- VALIDA QUE EXISTA EMPRESA EN GESTOR	-----------------
		DECLARE @TablaEmpresa TABLE
		(
		RutEmpresa VARCHAR(14)
		);
			
		IF exists(SELECT top 1 E.empresaid,C.RutEmpresa,E.empleadoid
					FROM Sodexo_Gestor.dbo.Excelempl Ex
					INNER JOIN Sodexo_Gestor.dbo.Empleados E on E.empleadoid = Ex.rutempleado COLLATE SQL_Latin1_General_CP1_CI_AS
					left join [Sodexo_RBK].dbo.empresas C ON E.empresaid = C.RutEmpresa COLLATE SQL_Latin1_General_CP1_CI_AS
					where C.RutEmpresa is null and Ex.rutusuario =@pusuarioid)
				
				BEGIN					
					
					INSERT INTO @TablaEmpresa
					SELECT DISTINCT E.empresaid
					FROM Sodexo_Gestor.dbo.Excelempl Ex 
					INNER JOIN Sodexo_Gestor.dbo.Empleados E on E.empleadoid = Ex.rutempleado COLLATE SQL_Latin1_General_CP1_CI_AS
					left join [Sodexo_RBK].dbo.empresas C ON E.empresaid = C.RutEmpresa COLLATE SQL_Latin1_General_CP1_CI_AS
					where C.RutEmpresa is null and Ex.rutusuario =@pusuarioid


					SELECT @Cadena = COALESCE(@Cadena + ',','') +  RutEmpresa
					FROM @TablaEmpresa
					
					Set @MsjE = 'La Empresa ' + isnull(@Cadena,'') + ' No existe.'
			
					IF (@debug = 1)
					BEGIN
			
						print @MsjE

					END
		
	
				END	


		-----------------------------	VALIDA SI EXISTE LUGAR PAGO	------------------------------
		DECLARE @TablaLugarPago TABLE
		(
		Lugarpago VARCHAR(14)
		);

			IF exists(SELECT top 1 E.LugarPago,L.lugarpagoid,E.empleadoid
				FROM Sodexo_Gestor.dbo.Excelempl Ex
				INNER JOIN Sodexo_Gestor.dbo.Empleados E on E.empleadoid COLLATE SQL_Latin1_General_CP1_CI_AS = Ex.rutempleado COLLATE SQL_Latin1_General_CP1_CI_AS
				left join [Sodexo_RBK].dbo.lugarespago L ON E.LugarPago COLLATE SQL_Latin1_General_CP1_CI_AS= L.lugarpagoid and E.empresaid = L.empresaid COLLATE SQL_Latin1_General_CP1_CI_AS
				where L.lugarpagoid is null and Ex.rutusuario =@pusuarioid)
			BEGIN
					 
					INSERT INTO @TablaLugarPago
					SELECT DISTINCT  E.LugarPago
					FROM Sodexo_Gestor.dbo.Excelempl Ex
					INNER JOIN Sodexo_Gestor.dbo.Empleados E on E.empleadoid COLLATE SQL_Latin1_General_CP1_CI_AS = Ex.rutempleado COLLATE SQL_Latin1_General_CP1_CI_AS
					left join Sodexo_RBK.dbo.lugarespago L ON E.LugarPago COLLATE SQL_Latin1_General_CP1_CI_AS = L.lugarpagoid and E.empresaid = L.empresaid COLLATE SQL_Latin1_General_CP1_CI_AS
					where L.lugarpagoid is null and Ex.rutusuario =@pusuarioid


				SELECT @Cadena = COALESCE(@Cadena + ',','') +  Lugarpago
				FROM @TablaLugarPago
					
					Set @MsjE = isnull(@MsjE,'')+'Lugar Pago ' + isnull(@Cadena,'') + ' No existe.'
				
				IF (@debug = 1)
				BEGIN
	
					print @MsjE
		
				END

			
			END
		
		
		-----------------------------	VALIDA SI EXISTE CENTRO COSTO	------------------------------
		DECLARE @TablaCentroCosto TABLE
		(
		CentroCosto VARCHAR(14)
		);

			IF exists(SELECT top 1 E.centrocostoid,C.centrocostoid,E.empleadoid
				FROM Sodexo_Gestor.dbo.Excelempl Ex
				INNER JOIN Sodexo_Gestor.dbo.Empleados E on E.empleadoid COLLATE SQL_Latin1_General_CP1_CI_AS = Ex.rutempleado COLLATE SQL_Latin1_General_CP1_CI_AS
				left join Sodexo_RBK.dbo.centroscosto C ON E.centrocostoid COLLATE SQL_Latin1_General_CP1_CI_AS = C.centrocostoid and c.lugarpagoid = E.LugarPago COLLATE SQL_Latin1_General_CP1_CI_AS
				where C.centrocostoid is null and Ex.rutusuario =@pusuarioid)
			BEGIN
					 
					INSERT INTO @TablaCentroCosto
					SELECT DISTINCT E.centrocostoid
					FROM Sodexo_Gestor.dbo.Excelempl Ex 
					INNER JOIN Sodexo_Gestor.dbo.Empleados E on E.empleadoid COLLATE SQL_Latin1_General_CP1_CI_AS = Ex.rutempleado COLLATE SQL_Latin1_General_CP1_CI_AS
					left join Sodexo_RBK.dbo.centroscosto C ON E.centrocostoid COLLATE SQL_Latin1_General_CP1_CI_AS = C.centrocostoid  and c.lugarpagoid = E.LugarPago COLLATE SQL_Latin1_General_CP1_CI_AS
					where C.centrocostoid is null and Ex.rutusuario =@pusuarioid


				SELECT @Cadena = COALESCE(@Cadena + ',','') +  CentroCosto
				FROM @TablaCentroCosto
					
					Set @MsjE = isnull(@MsjE,'')+'El Centro de Costo ' + isnull(@Cadena,'') + ' No existe.'
				
				IF (@debug = 1)
				BEGIN
	
					print @MsjE
		
				END

			
			END
	
		-----------------------------------------------------
		SET @lmensaje = @MsjE
		-----------------------------------------------------
		--SET @error = 1
		--IF (@lmensaje is null)
		--BEGIN			
			BEGIN TRY  
					BEGIN TRANSACTION 
					
					MERGE Sodexo_RBK.dbo.personas as TARGET
					USING(SELECT rutempleado,p.nombre,p.correo FROM Sodexo_Gestor.dbo.Excelempl e LEFT JOIN Sodexo_Gestor.dbo.personas p 
						on e.rutempleado COLLATE SQL_Latin1_General_CP1_CI_AS = p.personaid COLLATE SQL_Latin1_General_CP1_CI_AS WHERE rutusuario = @pusuarioid GROUP BY rutempleado,p.nombre,p.appaterno,p.apmaterno,p.correo
					) AS SOURCE(rutempleado,nombre,correo)
					ON (TARGET.personaid COLLATE SQL_Latin1_General_CP1_CI_AS = SOURCE.rutempleado COLLATE SQL_Latin1_General_CP1_CI_AS)
					WHEN MATCHED THEN
						UPDATE SET nombre = SOURCE.nombre,
									correo=SOURCE.correo
									--direccion = SOURCE.direccion,
									--comuna = SOURCE.comuna
									
					WHEN NOT MATCHED THEN
						INSERT (personaid, nombre,correo)
						VALUES (SOURCE.rutempleado, SOURCE.nombre,SOURCE.correo); 
						
						MERGE Sodexo_RBK.dbo.empleados as TARGET
					USING(SELECT e.rutempleado, case when p.estado=0 then 'A' ELSE 'E' END AS estado2,
					
					case when  p.rolid= 0 then 2 ELSE 1 END AS rolid,centrocostoid,empresaid,lugarpago  FROM Sodexo_Gestor.dbo.Excelempl e LEFT JOIN Sodexo_Gestor.dbo.empleados p on e.rutempleado COLLATE SQL_Latin1_General_CP1_CI_AS = p.empleadoid  COLLATE SQL_Latin1_General_CP1_CI_AS WHERE rutusuario = @pusuarioid GROUP BY rutempleado, p.estado ,p.rolid, centrocostoid,empresaid,lugarpago
					) AS SOURCE(rutempleado,estado2,rolid,centrocostoid,empresaid,lugarpagoid)
					ON (TARGET.empleadoid COLLATE SQL_Latin1_General_CP1_CI_AS = SOURCE.rutempleado COLLATE SQL_Latin1_General_CP1_CI_AS)
					WHEN MATCHED THEN
						UPDATE SET 
									Rutempresa = SOURCE.empresaid,
								    centrocostoid = SOURCE.centrocostoid	,	
									idEstadoEmpleado = SOURCE.estado2,
									rolid = SOURCE.rolid,
									lugarpagoid = SOURCE.lugarpagoid
					WHEN NOT MATCHED THEN
						--INSERT (empleadoid, empresaid,centrocostoid,rolid,idEstadoEmpleado, lugarpagoid)
						INSERT (empleadoid,rolid,idEstadoEmpleado,Rutempresa,centrocostoid,lugarpagoid)
						VALUES (SOURCE.rutempleado, SOURCE.rolid,SOURCE.estado2,SOURCE.empresaid,SOURCE.centrocostoid,SOURCE.lugarpagoid); 
					
					COMMIT TRANSACTION;
			END TRY  
				BEGIN CATCH  
					--SELECT @lmensaje = 'Hubo un error en la actualizacion del gestor, intente nuevamente. Si el problema persiste comuniquese con soporte.'
					--SELECT @lmensaje = @@ERROR
					SELECT @lmensaje = ERROR_MESSAGE()  
					SELECT @error = 1
					ROLLBACK TRANSACTION ;
				END CATCH ;
		--END	
		--ELSE
		--BEGIN
		--	set @error=1;
		--END
	END	
		SELECT @error AS error, @lmensaje AS mensaje 
END 
GO
