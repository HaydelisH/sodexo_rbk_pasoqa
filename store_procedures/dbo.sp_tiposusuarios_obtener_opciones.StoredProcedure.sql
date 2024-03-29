USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_tiposusuarios_obtener_opciones]    Script Date: 1/22/2024 7:21:15 PM ******/
SET ANSI_NULLS OFF
GO
SET QUOTED_IDENTIFIER OFF
GO
-- =============================================
-- Autor: Cristian Soto
-- Creado el: 1/09/2016
-- Descripcion:	Obtener tipo de usuario 
-- Ejemplo:exec sp_tiposusuarios_obtener_opciones 1,2
-- =============================================
CREATE PROCEDURE [dbo].[sp_tiposusuarios_obtener_opciones]
@ptipousuarioid		INT	,	-- identificador del tipo de usuario a consultar
@ptipousuarioingid	INT		-- identificador del tipo de usuario que esta consultando
	
AS	
BEGIN
	SET NOCOUNT ON;

	SELECT 
	OP_SIS.opcionid,
	OP_SIS.nombre,
	OP_SIS.detalle,
	OP_TUC.consulta,
	OP_TUC.modifica,
	OP_TUC.crea,
	OP_TUC.elimina,
	CASE WHEN OP_TUC.consulta = 1 THEN 'checked' ELSE '' END AS checkconsulta,
	CASE WHEN OP_TUC.modifica = 1 THEN 'checked' ELSE '' END AS checkmodifica,
	CASE WHEN OP_TUC.crea = 1     THEN 'checked' ELSE '' END AS checkcrea,
	CASE WHEN OP_TUC.elimina = 1  THEN 'checked' ELSE '' END AS checkelimina,
	CASE WHEN OP_TUC.ver = 1	  THEN 'checked' ELSE '' END AS checkver,
	
	CASE WHEN OP_TUI.consulta	IS NULL OR OP_TUI.consulta = 0 THEN 'disabled'  ELSE '' END AS disabled_consulta,
	CASE WHEN OP_TUI.modifica	IS NULL OR OP_TUI.modifica = 0 THEN 'disabled'  ELSE '' END AS disabled_modifica,
	CASE WHEN OP_TUI.crea		IS NULL OR OP_TUI.crea     = 0 THEN 'disabled'  ELSE '' END AS disabled_crea,
	CASE WHEN OP_TUI.elimina	IS NULL OR OP_TUI.elimina  = 0 THEN 'disabled'  ELSE '' END AS disabled_elimina,
	CASE WHEN OP_TUI.ver		IS NULL OR OP_TUI.ver      = 0 THEN 'disabled'  ELSE '' END AS disabled_ver

	FROM opcionessistema AS OP_SIS
	LEFT JOIN opcionesxtipousuario OP_TUC	ON	OP_TUC.tipousuarioid	= @ptipousuarioid 
											AND OP_TUC.opcionid			= OP_SIS.opcionid
	LEFT JOIN opcionesxtipousuario OP_TUI	ON	 @ptipousuarioingid		= OP_TUI.tipousuarioid	 
											AND  OP_SIS.opcionid		= OP_TUI.opcionid
											
	ORDER BY orden			 
		
			
	RETURN
END
GO
