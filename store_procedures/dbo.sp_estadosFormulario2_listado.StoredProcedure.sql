USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_estadosFormulario2_listado]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernanddez
-- Creado el: 04-02-2020
-- Descripcion: Listar los estado de gestion
-- Ejemplo:  sp_estadosFormulario2_listado
-- =============================================
CREATE PROCEDURE [dbo].[sp_estadosFormulario2_listado]

AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;
	
	DECLARE @lmensaje	VARCHAR(100)
	DECLARE @error		INT
    
    SELECT 
		estadoFormulario.estadoFormularioid AS idEstadoGestion,
		estadoFormulario.nombre AS Descripcion
	FROM estadoFormulario
	WHERE estadoFormulario.estadoFormularioid IN (4,5)
		
END
GO
