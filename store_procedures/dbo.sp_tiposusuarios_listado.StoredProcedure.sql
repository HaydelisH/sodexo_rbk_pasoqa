USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_tiposusuarios_listado]    Script Date: 1/22/2024 7:21:15 PM ******/
SET ANSI_NULLS OFF
GO
SET QUOTED_IDENTIFIER OFF
GO
-- =============================================
-- Autor: Cristian Soto
-- Creado el: 15/06/2016
-- Descripcion:	Consulta listado de tipos de usuario
-- Ejemplo:exec sp_tiposusuarios_listado 1
-- =============================================
CREATE PROCEDURE [dbo].[sp_tiposusuarios_listado]
@ptipousuarioid	INT			-- id tipo de usuario

	
AS	
BEGIN
	SET NOCOUNT ON;

	IF (@ptipousuarioid <> 1)
	BEGIN
		SELECT 
		tipousuarioid,
		nombre,
		diasinactividad,
		ROW_NUMBER()Over(Order by tipousuarioid) As RowNum
		FROM tiposusuarios
		WHERE tipousuarioid <> 1
	END
	
	IF (@ptipousuarioid = 1)
	BEGIN
		SELECT 
		tipousuarioid,
		nombre,
		diasinactividad,
		ROW_NUMBER()Over(Order by tipousuarioid) As RowNum
		FROM tiposusuarios
	END
		
	RETURN;
END
GO
