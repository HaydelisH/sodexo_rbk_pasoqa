USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_ObtenerTablaDatosInformes]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Author:        RC
-- Create date: 20200121
-- Description:   [sp_ObtenerTablaDatosInformes] 'Tabla1', 101
-- Description:   [sp_ObtenerTablaDatosInformes] 'Representantes', 6
-- Description:   [sp_ObtenerTablaDatosInformes] 'Empleados', 7
-- Obtiene los datos para reemplazar las variables del cuerpo del correo 
-- =============================================
CREATE PROCEDURE [dbo].[sp_ObtenerTablaDatosInformes] 
      @TablaDatos varchar(50),
      @Correlativo int
AS
BEGIN
      
      SET NOCOUNT ON;
      DECLARE @Tabla Varchar(8000) 
      
      DECLARE @html nvarchar(MAX) = NULL
      DECLARE @Estado int

      DECLARE @TableT TABLE  
            ( Tipo   varchar(30) NULL ,  
              Fecha  varchar(30) NULL,
              Total int  ); 
      
--idEstado  Descripcion
--1   Generado en espera de aprobacion
--2   Pendiente por firma Representante
--3   Pendiente por firma Empleado
--4   Generado Manualmente
--6   Firmado
--7   Pendiente-Terminado
--8   Rechazado
--9   Pendiente por firma Notario
--10  Pendiente por firma Representante 2 
      
      DECLARE @RutFirmante varchar(12)
      SELECT @RutFirmante = RutUsuario FROM EnvioCorreosInforme where Correlativo = @Correlativo
      
      IF (@TablaDatos = 'REPRESENTANTE')
      BEGIN                                                                                                                
                             SET @Estado = 2
     END         

      IF (@TablaDatos = 'Empleados')
      BEGIN                                                                             
                             SET @Estado = 3
     END         

                             INSERT INTO @TableT
                             SELECT 
                                   CASE ROW_NUMBER() OVER( PARTITION BY TD.NombreTipoDoc ORDER BY TD.NombreTipoDoc)
                                         WHEN 1 THEN TD.NombreTipoDoc
                                         ELSE ''
                                   END  as  'Tipo'
                                   ,NULL
                                   --,convert (varchar(10), [FechaCreacion], 105) as Fecha
                                   ,count(C.idDocumento) Total
                             FROM [dbo].Contratos C
                                   INNER JOIN ContratoDatosVariables D on C.idDocumento = D.idDocumento           
                                   LEFT JOIN [dbo].ContratoFirmantes CF on CF.idDocumento = C.idDocumento AND C.idEstado = CF.idEstado
                                   INNER JOIN [dbo].Empresas EM on EM.RutEmpresa = C.RutEmpresa  
                                   INNER JOIN [dbo].Plantillas CT on C.idPlantilla = CT.idPlantilla
                                   INNER JOIN TipoDocumentos TD on TD.idTipoDoc = CT.idTipoDoc
                             WHERE C.idEstado = @Estado
                             AND C.Eliminado = 0
                             AND CF.RutFirmante = @RutFirmante                          
                             GROUP BY TD.NombreTipoDoc --, convert (varchar(10), FechaCreacion, 105)                                                               

                                         SELECT @HTML = (
                                         SELECT CONVERT(NVARCHAR(MAX), (SELECT
                                   (SELECT '' /*'Informe Documentos Pendientes('
                                         +CONVERT(CHAR(11),GETDATE(),113)
                                         +')'*/ FOR XML PATH(''), TYPE) AS 'caption',
                                   --(SELECT 
                                   --          -- 'Tipo' AS th,             
                                   --          -- 'Fecha' AS th,
                                   --          'Total' AS th
                                   --          FOR XML RAW('tr'), ELEMENTS, TYPE) AS 'thead',
                                   (
                                         /* INICIO SELECT */
                                               select 
                                                     Tipo as TD,
                                                     --Fecha as TD,
                                                     --'ago' as 'TD/@Special',
                                                     Total   as TDC                                             
                                                     
                                               FROM @TableT
                                               WHERE Total != 0                                           
                                         /* FIN SELECT */
                                 -- FOR XML PATH('tr'), TYPE  
                                   FOR XML RAW('tr'), ELEMENTS, TYPE
                                    ) AS 'tbody'
                               FOR XML PATH(''), ROOT('table')))
                             )     
                  SET @html = REPLACE(@html, '<TDC>', '<TD style="text-align: center;">')
                  SET @html = REPLACE(@html, '</TDC>', '</TD>')                                
                  SET @Tabla = @html
                        

      SELECT Top 1
         @Correlativo                    AS "@@NumeroContrato@@",
         U.usuarioid                     AS "@@RutEmpleado@@",
         P.Nombre                        AS "@@NombreEmpleado@@" ,     
         @Tabla                          AS "@CuerpoInforme@"    
         --U.Grupo                       AS "@@Grupo@@"       
      FROM dbo.Usuarios U          
      INNER JOIN dbo.EnvioCorreosInforme E ON U.usuarioid = E.RutUsuario
      INNER JOIN dbo.personas P ON U.usuarioid = P.personaid
      where E.Correlativo = @Correlativo       
      
END
GO
