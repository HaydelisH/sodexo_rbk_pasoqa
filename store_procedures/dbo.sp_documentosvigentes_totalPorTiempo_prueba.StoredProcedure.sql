USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_documentosvigentes_totalPorTiempo_prueba]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

-- =============================================
-- Autor: RC
-- Creado el: 12/07/2018
-- Descripcion: Muestra el listado de de Documetnos Generados 
-- Ejemplo:exec [sp_documentos_reportes] '', 2, 1, 10  
-- =============================================
CREATE PROCEDURE [dbo].[sp_documentosvigentes_totalPorTiempo_prueba]

	@ptipousuarioid			INT,	-- id del tipo de usuario o perfil
	@pagina					INT,	-- numero de pagina
	@decuantos          DECIMAL,	-- total pagina
	@pidDocumento			INT,	-- Id Documento
	@pidtipodocumento		INT,	-- TipoDocumento
	@pidEstadoContrato		INT,	-- Estado del Contrato
	@pidTipoFirma			INT,	-- 1 = Manual y 2 = Elctronico
	@pidProceso				INT,	-- Id del Proceso
	@pFirmante		varchar(100),	-- Firmante del Contrato
	@pusuarioid		varchar(50),	-- id usuario
	@pfichaid				INT,	-- Fichaid
	@fechaInicio			DATE,	-- Fecha inicio
	@fechaFin				DATE,	-- Fecha fin 
	@empresaid		nvarchar(10),	-- Rut empresa 
	@lugarpagoid	nvarchar(14),	-- Lugar de pago 
	@centrocostoid  nvarchar(14),	-- Centro de costo 
	@pidPlantilla int,
	@debug			tinyint	= 0		-- DEBUG 1= imprime consulta
AS
BEGIN
	SET NOCOUNT ON;
	DECLARE @total INT
	DECLARE @totalorig INT
	DECLARE @totalreg  DECIMAL (9,2)
	DECLARE @nl   char(2) = char(13) + char(10)
	DECLARE @FirmanteLIKE	VARCHAR(100)									  
	DECLARE @centrocostoLIKE VARCHAR(100);
	SET @centrocostoLIKE = '%' + @centrocostoid + '%'
              
	DECLARE @sqlString nvarchar(max)
	
	SET @FirmanteLIKE = '%' + @pFirmante + '%'; 
	 
    DECLARE @vdecimal DECIMAL (9,2)
    
    DECLARE @rolid			INT
	DECLARE @lmensaje		VARCHAR(100)

    DECLARE @base_gestor		VARCHAR(120);
    SELECT @base_gestor = parametro FROM Parametros WHERE idparametro = 'gestor'	
	
	--Buscar el rol del usuario
	SELECT @rolid = rolid FROM Usuarios WHERE usuarioid = @pusuarioid	
	
	SET @sqlString = N'	
	    With DocumentosTabla
      as (
            SELECT  
                C.idDocumento
            FROM Contratos C
            INNER JOIN Documentos D ON D.idDocumento = C.idDocumento
            INNER JOIN Plantillas PL on PL.idPlantilla = C.idPlantilla
            INNER JOIN tiposdocumentosxperfil T ON PL.idTipoDoc = T.idtipodoc AND T.tipousuarioid = @ptipousuarioid
            INNER JOIN ContratoDatosVariables CDV ON CDV.idDocumento = C.idDocumento
            INNER JOIN accesoxusuarioccosto     ACC             
                        ON 
                        ACC.empresaid = C.RutEmpresa 
                        AND ACC.centrocostoid = CDV.CentroCosto
                        AND ACC.lugarpagoid = CDV.lugarpagoid
                        AND ACC.usuarioid = @pusuarioid                           
			INNER JOIN WorkflowProceso ON WorkflowProceso.idWF = C.idWF
				AND WorkflowProceso.tipoWF IS NULL
            INNER JOIN Empleados Emp ON CDV.Rut = Emp.empleadoid
           -- LEFT JOIN ' + @base_gestor + '.dbo.empleados empleadosGestor ON CDV.Rut = empleadosGestor.empleadoid COLLATE SQL_Latin1_General_CP1_CI_AS
           -- LEFT JOIN ' + @base_gestor + '.dbo.estados estadosGestor ON estadosGestor.estadoid = empleadosGestor.estado
            ' + @nl
                                                                                                                                     
    --Validar el rol
    SET @sqlString += N' WHERE C.eliminado = 0 ' + @nl                                                                   
                    
    IF( @rolid = 2 ) --1 : Privado y 2: Público
    BEGIN
        SET @sqlString += N' AND Emp.rolid = @rolid ' + @nl
    END
                                
    IF( @pidEstadoContrato > 0 )
    BEGIN
                    SET @sqlString += ' AND C.idEstado = @pidEstadoContrato ' + @nl
    END       
                    
    IF( @pidEstadoContrato = -1 ) --Todos los estados
    BEGIN
                    SET @sqlString += ' AND C.idEstado != 7 ' + @nl                                                              
    END
    
    IF( @pidEstadoContrato = 0 ) --En proceso de firma
    BEGIN
                    SET @sqlString += ' AND C.idEstado IN (2,3,10) ' + @nl
    END

    IF( @pidEstadoContrato = -2 ) --Otros estados 
    BEGIN
                    SET @sqlString += ' AND C.idEstado IN (1,4) ' + @nl
    END

    IF (@pidDocumento != 0)
    BEGIN
                    SET @sqlString += ' AND C.idDocumento = @pidDocumento ' + @nl
    END                                    

    IF (@pFirmante != '')
    BEGIN
                    SET @sqlString += ' AND  CDV.Rut = @pFirmante ' + @nl
    END       

    IF (@pidtipodocumento != 0)
    BEGIN
                    SET @sqlString += ' AND PL.idTipoGestor = @pidtipodocumento' + @nl
    END   
	
	IF (@pidPlantilla != 0)
    BEGIN
                     SET @sqlString += ' AND PL.idPlantilla = @pidPlantilla' + @nl
    END 
    
    IF (@pidTipoFirma != 0)
    BEGIN
                    SET @sqlString += ' AND C.idTipoFirma = @pidTipoFirma' + @nl
    END
                                
    IF (@pidProceso != 0)
    BEGIN
                    SET @sqlString += ' AND C.idProceso = @pidProceso' + @nl
    END
    
    IF ( @fechaInicio IS NOT NULL AND @fechaFin IS NULL)  
    BEGIN
                    SET @fechaFin = DATEADD (DAY, 1,@fechaInicio)
                    SET @sqlString += ' AND C.FechaCreacion BETWEEN @fechaInicio AND @fechaFin' + @nl
    END
    
    IF ( @fechaInicio IS NOT NULL AND @fechaFin IS NOT NULL)  
    BEGIN
                    SET @sqlString += ' AND C.FechaCreacion BETWEEN @fechaInicio AND @fechaFin' + @nl
    END
    
    IF ( @fechaInicio IS NULL AND @fechaFin IS NOT NULL)  
    BEGIN
                    SET @sqlString += ' AND C.FechaCreacion <= @fechaFin' + @nl
    END       
    
    IF ( @empresaid != '' AND @empresaid != '0' )  
    BEGIN
                    SET @sqlString += ' AND C.RutEmpresa = @empresaid' + @nl
    END       
    
    IF ( @centrocostoid != '' AND @centrocostoid != '0' )   
    BEGIN
                    SET @sqlString += ' AND CDV.CentroCosto = @empresaid' + @nl
    END       
    IF ( @lugarpagoid != '' AND @lugarpagoid != '0' )   
    BEGIN
                    SET @sqlString += ' AND CDV.lugarpagoid = @lugarpagoid' + @nl
    END       
    
			SET @sqlString += N') 
					  SELECT 
                            @totalorig = count(idDocumento)
					  FROM DocumentosTabla
					  '                              

			DECLARE @Parametros nvarchar(max)
			
			SET @Parametros =  N'@ptipousuarioid INT, @PidDocumento INT, @pidtipodocumento INT,
							 @pidEstadoContrato INT, @pidTipoFirma INT,
							 @pidProceso INT,@pFirmante VARCHAR(100),
							 @pusuarioid VARCHAR(50),@FirmanteLIKE VARCHAR(100), 
							 @lmensaje VARCHAR(100),@rolid INT,@pfichaid INT, @fechaInicio DATE,
							 @fechaFin DATE,@empresaid nvarchar(10), @lugarpagoid nvarchar(14), 
							 @centrocostoid  nvarchar(14),@centrocostoLIKE VARCHAR(100),@pidPlantilla INT,@totalorig INT OUTPUT'

			SET @sqlString += ' OPTION (RECOMPILE)'
						
			IF (@debug = 1)
			BEGIN
				PRINT @sqlString
			END

			EXECUTE sp_executesql @sqlString, @Parametros, 
								  @ptipousuarioid,@PidDocumento,@pidtipodocumento,
								  @pidEstadoContrato,@pidTipoFirma,@pidProceso,@pFirmante,@pusuarioid, 
								  @FirmanteLIKE,@lmensaje,@rolid, @pfichaid, @fechaInicio,@fechaFin,
							  	  @empresaid, @lugarpagoid, @centrocostoid, @centrocostoLIKE,@pidPlantilla,
								  @totalorig = @totalorig OUTPUT
										
						
			SELECT @totalreg = (@totalorig/@decuantos)
			
			SELECT @vdecimal  = @totalreg - convert(integer,  @totalreg)
	            
			 IF @vdecimal > 0 
				SELECT @total = @totalreg + 1
			 ELSE
				SELECT @total = @totalreg
				
			SET @totalreg = @totalreg * @decuantos
		 
		select  @total as total, @totalreg as totalreg	                                                                       
	 RETURN                   
END
GO
