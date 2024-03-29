USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_reporteDeclaracionFirmaElectronica_listado]    Script Date: 1/22/2024 7:21:15 PM ******/
SET ANSI_NULLS OFF
GO
SET QUOTED_IDENTIFIER OFF
GO

-- =============================================
-- Autor: LMM
-- Creado el: 25/11/2020
-- Descripcion:  Consulta listado formulario Autoevaluacion de Riesgo
-- Modificado: gdiaz 13/04/2021
-- Ejemplo:exec sp_reporteDeclaracionFirmaElectronica_listado
-- =============================================
CREATE PROCEDURE [dbo].[sp_reporteDeclaracionFirmaElectronica_listado]
@pdocumentoid		int,
@pempleadoid		VARCHAR(10),
@pnombre            NVARCHAR(50),             
@pfechacreacionini  DATE,  
@pfechacreacionfin  DATE, 
@pestadoid			int,
@pagina             INT,                                     
@decuantos          DECIMAL    ,
@prutempresa		VARCHAR(10)                                     

                
AS          
BEGIN
	SET NOCOUNT ON;
	DECLARE @nombrelike NVARCHAR(50)
	DECLARE @xfechadesde    DATETIME
	DECLARE @xfechahasta    DATETIME

	IF @pfechacreacionini IS NOT NULL
		SET @xfechadesde =  DATEADD(d,DATEDIFF(d,0,@pfechacreacionini),0)
		
		
	IF @pfechacreacionfin IS NOT NULL
		SET @xfechahasta =  CAST(CONVERT(CHAR(8), @pfechacreacionfin, 112) + ' 23:59:59.99' AS DATETIME)

                
	SET @nombrelike = '%' + @pnombre + '%';        

	--truncate table autoevaluacion_tmp;
	
	SELECT 
		empleadoid,
		estadoFormularioid,
		nomestado,
		idDocumento,
		nombre,
		fechaCarga,
		RutEmpresa,
		correoNotificacionPorConcentimiento,
		RowNum
	FROM 
	(
		SELECT 
            EF.empleadoid,
            EF.estadoFormularioid,
            CASE WHEN EF.estadoFormularioid = 1 THEN 'Espera de firma'
				--WHEN EF.estadoFormularioid = 2 THEN 'Espera de firma'
    --            WHEN EF.estadoFormularioid = 6 THEN 'Firmado'
				WHEN EF.estadoFormularioid = 7 THEN 'Firmado'
                ELSE '' 
            END AS nomestado,
            EF.idDocumento,
            ISNULL(PE.nombre,'') + ' ' + ISNULL(PE.appaterno,'') + ' ' + ISNULL(PE.apmaterno,'') AS nombre,
            CONVERT(VARCHAR(10),EF.fechaCarga,105)  AS fechaCarga , 
            CON.RutEmpresa,
            ContratoDatosVariables.correoNotificacionPorConcentimiento,
            ROW_NUMBER()Over(Order by EF.empleadoFormularioid) As RowNum
		FROM empleadoFormulario AS EF
		LEFT JOIN personas AS PE ON EF.empleadoid = PE.personaid
		INNER JOIN contratos AS CON ON  EF.idDocumento = CON.idDocumento
        INNER JOIN ContratoDatosVariables ON ContratoDatosVariables.idDocumento = CON.idDocumento
		WHERE 
				( EF.idFormulario = 1 ) AND
             ( EF.empleadoid = @pempleadoid OR @pempleadoid = '')
            AND ((EF.fechaCarga BETWEEN @xfechadesde AND @xfechahasta) OR (@pfechacreacionini IS  NULL OR @pfechacreacionfin IS NULL))
            AND (EF.idDocumento = @pdocumentoid OR @pdocumentoid = 0)
            AND (EF.estadoFormularioid = @pestadoid OR @pestadoid = 0)
            AND (PE.nombre LIKE @nombrelike COLLATE Modern_Spanish_CI_AI)
            AND (CON.rutempresa = @prutempresa or @prutempresa = '0')
            AND CON.Eliminado = 0

	)  ResultadoPaginado
	WHERE RowNum BETWEEN (@pagina - 1) * @decuantos + 1 
	AND @pagina * @decuantos    
	                
          
     RETURN;
END
GO
