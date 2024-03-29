USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_WF_Consultas_para_Firmas_20211115_HH]    Script Date: 1/22/2024 7:21:15 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE procedure [dbo].[sp_WF_Consultas_para_Firmas_20211115_HH]
as
BEGIN

SELECT *
  FROM [dbo].[WorkflowEstadoProcesos] WEP
  inner join dbo.WorkflowProceso WP on WEP.idWorkflow = WP.idWF
  inner join ContratosEstados EW on WEP.idEstadoWF = EW.idEstado
  where WEP.idWorkflow = 1
  order by Orden



SELECT *
  FROM [dbo].[Contratos] C
  inner join ContratosEstados E on C.idEstado = E.idEstado 
  inner join WorkflowEstadoProcesos WEP on C.idWF = WEP.idWorkflow
  inner join ContratosEstados EW on WEP.idEstadoWF = EW.idEstado 
  and C.idEstado = EW.idEstado
  
  

SELECT *
  FROM ContratoFirmantes CF 
  where idEstado = 3 and firmado = 0
END
GO
