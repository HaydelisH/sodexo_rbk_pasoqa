USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_opcionesxtipousuario_rlpendientefirma]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Cristian Soto
-- Creado el: 22/11/2020
-- Modificado: CSB
-- Descripcion: para deducir si se agrega acceso de pendiente de firma rrrll
-- Ejemplo:exec [sp_opcionesxtipousuario_rlpendientefirma] 
-- =============================================
create  PROCEDURE [dbo].[sp_opcionesxtipousuario_rlpendientefirma]
	@usuarioid	VARCHAR(10),
	@opcionid	VARCHAR(50)

AS
BEGIN	
	SET NOCOUNT ON;			
 
	
	select COUNT(*) as pendiente
	--CF.RutFirmante,CF.*,CO.idEstado,CO.FechaCreacion,WPR.idWF
	from ContratoFirmantes CF
	inner join  Contratos CO				ON CO.idDocumento	= CF.idDocumento
	inner join  WorkflowProceso WPR			on CO.idWF			= WPR.idWF
	where CF.RutFirmante = @usuarioid
	and  WPR.tipoWF = 1
	and CF.idEstado = CO.idEstado
	and CF.Firmado = 0
	and co.Eliminado = 0

END
GO
