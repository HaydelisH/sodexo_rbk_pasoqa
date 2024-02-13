USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_procesos_todos]    Script Date: 1/22/2024 7:21:15 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 04-04-2019
-- Descripcion:  Obtiene todos los procesos disponibles
-- Ejemplo:exec sp_procesos_listado
-- =============================================
CREATE PROCEDURE [dbo].[sp_procesos_todos]
	@ptipousuarioid			INT,	-- id del tipo de usuario o perfil
	@pagina					INT,	-- numero de pagina
	@decuantos          DECIMAL,	-- total pagina
	@buscar				VARCHAR(20), -- Buscar un proceso
	@pusuarioid		varchar(50),	-- id usuario
	@debug			tinyint	= 0		-- DEBUG 1= imprime consulta
AS
BEGIN

	DECLARE @Pinicio		INT 
	DECLARE @Pfin			INT
	DECLARE @nl				char(2) = char(13) + char(10)
	DECLARE @buscarLIKE VARCHAR(50)

	SET @Pinicio = (@pagina - 1) * @decuantos + 1 
	SET @Pfin = @pagina * @decuantos	
	
	SET @buscarLIKE = '%' + @buscar + '%'	
	
	DECLARE @rolid			INT
	DECLARE @lmensaje		VARCHAR(100)

	--Buscar el rol del usuario
	SELECT @rolid = rolid FROM Usuarios WHERE usuarioid = @pusuarioid		
    
	
	DECLARE @sqlString nvarchar(max)
	
	SET @sqlString = N'	
		With DocumentosTabla
		as 
		(
			SELECT 
				P.idProceso,
				P.Descripcion, 
				COUNT(*) As CantDocumentos,
				ROW_NUMBER()Over(Order by P.idProceso) As RowNum
			FROM 
				Procesos P 
			INNER JOIN Contratos C ON C.idProceso = P.idProceso
			INNER JOIN Documentos D ON D.idDocumento = C.idDocumento
			INNER JOIN Plantillas PL ON PL.idPlantilla = C.idPlantilla 
			INNER JOIN ContratoDatosVariables CDV ON CDV.idDocumento = C.idDocumento 
			--INNER JOIN accesodocxperfilccosto ACC	ON ACC.empresaid = C.RutEmpresa AND ACC.centrocostoid = CDV.CentroCosto AND ACC.tipousuarioid = @ptipousuarioid	
			--INNER JOIN accesodocxperfilccosto ACC	ON ACC.empresaid = C.RutEmpresa AND ACC.lugarpagoid = CDV.CentroCosto AND ACC.tipousuarioid = @ptipousuarioid AND ACC.centrocostoid = CDV.lugarpagoid
			INNER JOIN accesoxusuarioccosto     ACC	
            ON ACC.empresaid = C.RutEmpresa 
            AND ACC.centrocostoid = CDV.CentroCosto 
            AND ACC.lugarpagoid = CDV.lugarpagoid 
            --AND ACC.departamentoid = CDV.departamentoid 
            AND ACC.usuarioid = @pusuarioid 
			INNER JOIN tiposdocumentosxperfil TAPP	ON TAPP.idtipodoc = PL.idTipoDoc AND TAPP.tipousuarioid = @ptipousuarioid 
			INNER JOIN WorkflowProceso ON WorkflowProceso.idWF = C.idWF
			AND WorkflowProceso.tipoWF IS NULL
			' + @nl
	
	--Validar el rol
	IF( @rolid = 2 ) --1 : Privado y 2: Público
	BEGIN
		SET @sqlString += N' INNER JOIN Empleados Emp ON CDV.Rut = Emp.empleadoid AND Emp.rolid = @rolid ' + @nl
	END

	SET @sqlString += N'WHERE P.Eliminado = 0'  + @nl
	
	IF( @buscar != '' )
		BEGIN 
			SET @sqlString += ' AND P.Descripcion LIKE @buscarLIKE ' + @nl
		END
	
	SET @sqlString += '	 GROUP BY P.idProceso, P.Descripcion '  + @nl
			
	SET @sqlString += N') 
				  SELECT 
						idProceso,
						Descripcion,
						CantDocumentos,
						Rownum
				  FROM DocumentosTabla
				  WHERE	RowNum BETWEEN @Pinicio AND @Pfin'        
				  
	DECLARE @Parametros nvarchar(max)
		
	SET @Parametros =  N'@ptipousuarioid INT, @Pinicio INT, @Pfin INT, @buscarLIKE VARCHAR(50), @pusuarioid VARCHAR(50), @rolid INT'
	
	IF (@debug = 1)
	BEGIN
		PRINT @sqlString
	END

	EXECUTE sp_executesql @sqlString, @Parametros, @ptipousuarioid, @Pinicio, @Pfin, @buscar, @pusuarioid, @rolid
	
    RETURN                                                             
END
GO
