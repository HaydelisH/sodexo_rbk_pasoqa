USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_WF_Consultas_para_Firmas]    Script Date: 1/22/2024 7:21:15 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[sp_WF_Consultas_para_Firmas]
as
BEGIN

SELECT 
	idWorkflow,
	idEstadoWF,
	Orden,
	WEP.DiasMax,
	ConOrden,
	idWF,
	NombreWF,
	WP.DiasMax,
	Eliminado,
	PorEnte,
	tipoWF,
	idEstado,
	Descripcion,
	VerWF,
	VerWF_RL
  FROM [dbo].[WorkflowEstadoProcesos] WEP
  inner join dbo.WorkflowProceso WP on WEP.idWorkflow = WP.idWF
  inner join ContratosEstados EW on WEP.idEstadoWF = EW.idEstado
  where WEP.idWorkflow = 1
  order by Orden

SELECT	
	C.idDocumento
	,C.idEstado
	,C.idWF
	,C.FechaCreacion
	,C.FechaUltimaFirma
	,C.idTipoFirma
	,C.idPlantilla
	,C.DocCode
	,C.Eliminado
	,C.Observacion
	,C.idProceso
	,C.Enviado
	,C.idTipoGeneracion
	,C.RutEmpresa
	,E.idEstado
	,E.Descripcion
	,E.VerWF
	,E.VerWF_RL
	,idWorkflow
	,idEstadoWF
	,Orden
	,DiasMax
	,ConOrden
	,EW.idEstado
	,EW.Descripcion
	,EW.VerWF
	,EW.VerWF_RL
  FROM [dbo].[Contratos] C
  inner join ContratosEstados E on C.idEstado = E.idEstado 
  inner join WorkflowEstadoProcesos WEP on C.idWF = WEP.idWorkflow
  inner join ContratosEstados EW on WEP.idEstadoWF = EW.idEstado 
  and C.idEstado = EW.idEstado
  
SELECT 
	RutEmpresa
	,idDocumento
	,RutFirmante
	,idEstado
	,Firmado
	,FechaFirma
	,DiasTardoFirma
	,Orden
	,OrdenMismoEstado
	--,CodigoAuditoriaRBK
	--,CodigoAuditoriaFA
   FROM ContratoFirmantes CF 
  where idEstado = 3 and firmado = 0
END
GO
