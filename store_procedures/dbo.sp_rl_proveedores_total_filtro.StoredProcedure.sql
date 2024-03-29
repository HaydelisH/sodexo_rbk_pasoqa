USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_rl_proveedores_total_filtro]    Script Date: 1/22/2024 7:21:15 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Cristian Soto
-- Creado el: 21/08/2020
-- Descripcion: Total 
-- Modificado por: Gdiaz 11/01/2021
-- Ejemplo:exec [sp_rl_proveedores_total_filtro] '',1,10
-- =============================================
CREATE PROCEDURE [dbo].[sp_rl_proveedores_total_filtro]
	@buscar			VARCHAR(50),
	@pagina			INT,	-- numero de pagina
	@decuantos      DECIMAL	-- total pagina

AS
BEGIN
	DECLARE @nombrelike		NVARCHAR(52)
	DECLARE @totalreg		DECIMAL (9,2)
	DECLARE @vdecimal		DECIMAL (9,2)
	DECLARE @total			INT
	DECLARE @cantreg		INT

	SET @nombrelike = '%' + UPPER(RTRIM(@buscar)) + '%';	



	SELECT @cantreg = COUNT(*)
	FROM 
		(	
		SELECT 
		RutProveedor
		FROM rl_Proveedores
		WHERE ( UPPER(RTRIM(NombreProveedor)) LIKE @nombrelike 
		OR RutProveedor = @buscar)
		AND Eliminado = 0
		
		) as comosifuerauntabla

	SET @totalreg = (@cantreg /@decuantos)

	SELECT @vdecimal  = @totalreg - convert(integer,  @totalreg)
		
	IF @vdecimal > 0 
		SELECT @total = @totalreg + 1
	ELSE
		SELECT @total = @totalreg
					
	select @total as total, @cantreg as totalreg
	
	RETURN
END
GO
