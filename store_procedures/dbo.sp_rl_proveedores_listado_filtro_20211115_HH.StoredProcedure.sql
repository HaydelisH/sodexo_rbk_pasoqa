USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_rl_proveedores_listado_filtro_20211115_HH]    Script Date: 1/22/2024 7:21:15 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Cristian Soto
-- Creado el: 21/08/2020
-- Descripcion: Listado 
-- Modificado por: Gdiaz 11/01/2021
-- Ejemplo:exec [sp_rl_proveedores_listado_filtro] '',1,10
-- =============================================
CREATE PROCEDURE [dbo].[sp_rl_proveedores_listado_filtro_20211115_HH]
	@buscar			VARCHAR(50),
	@pagina			INT,	-- numero de pagina
	@decuantos      DECIMAL	-- total pagina

AS
BEGIN
	DECLARE @nombrelike VARCHAR(52)

	SET @nombrelike = '%' + UPPER(RTRIM(@buscar)) + '%';	

	SELECT *
	FROM 
		(	
		SELECT 
		RutProveedor,
		NombreProveedor,
		Direccion,
		Comuna,
		Ciudad,
		Eliminado,
		ROW_NUMBER()Over(Order by RutProveedor) As RowNum
		FROM rl_Proveedores
		WHERE ( UPPER(RTRIM(NombreProveedor)) LIKE @nombrelike 
		OR RutProveedor = @buscar)
		AND Eliminado = 0
		)  ResultadoPaginado
		WHERE RowNum BETWEEN (@pagina - 1) * @decuantos + 1 
		AND @pagina * @decuantos	

		RETURN
END
GO
