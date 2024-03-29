USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_contratodatosvariables_perfilObtener]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez 
-- Creado el: 25/03/2019
-- Descripcion: Obtiene las variables diponibles de un documento subido por carga masiva 
-- Ejemplo:exec [sp_contratodatosvariables_perfilObtener] 
-- sp_contratodatosvariables_perfilObtener '13559051-7'
-- =============================================
CREATE PROCEDURE [dbo].[sp_contratodatosvariables_perfilObtener]
      @Rut VARCHAR(10)
AS
BEGIN 
      SET NOCOUNT ON;              
 
      --SELECT 
      --    CV.CentroCosto AS idCentroCosto,
 --       CV.lugarpagoid AS lugarpagoid,
 --       lp.empresaid AS RutEmpresa
 --   FROM 
      --    ContratoDatosVariables CV
 --   INNER JOIN centroscosto cc ON CV.lugarpagoid = cc.lugarpagoid and CV.CentroCosto = cc.centrocostoid
 --   INNER JOIN lugarespago lp ON lp.lugarpagoid = CV.lugarpagoid
 --   WHERE 
 --       CV.Rut = @Rut
      --ORDER BY idDocumento DESC
      
      SELECT top 1  RutEmpresa     
                  ,CDV.centrocosto AS idCentroCosto
                  ,CDV.lugarpagoid AS lugarpagoid
                  ,CDV.idDocumento AS idDocumento
      FROM Contratos C
      INNER JOIN ContratoDatosVariables  CDV on C.idDocumento = CDV.idDocumento
      WHERE CDV.Rut = @Rut
      and C.eliminado = 0
      ORDER BY C.idDocumento DESC
      
END
GO
