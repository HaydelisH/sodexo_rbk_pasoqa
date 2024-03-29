USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_empleados_total]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 02/10/2019
-- Descripcion: Listado de Empleados 
-- Ejemplo:exec sp_empleados_listado
-- =============================================
CREATE PROCEDURE [dbo].[sp_empleados_total]
	@pagina		INT,
	@decuantos	INT, 
	@buscar		VARCHAR(100)
AS
BEGIN
	
	SET NOCOUNT ON;
	
	DECLARE @nombrelike NVARCHAR(50)
	DECLARE @totalreg   DECIMAL (9,2)
	DECLARE @vdecimal   DECIMAL (9,2)
	DECLARE @total		INT                       
                
    SET @nombrelike = '%' + @buscar + '%';        
                
	SELECT @totalreg = COUNT(*) FROM (
		SELECT 
			P.nombre,
			P.appaterno,
			P.apmaterno,
			P.personaid,
			E.rolid,
			R.Descripcion,
			ROW_NUMBER()Over(Order by P.personaid) As RowNum
		FROM Empleados E 
			INNER JOIN personas P ON E.empleadoid = p.personaid
			LEFT JOIN Roles R ON E.rolid = R.rolid
		WHERE (P.nombre LIKE @nombrelike  COLLATE Modern_Spanish_CI_AI)
		   OR (P.appaterno LIKE @nombrelike  COLLATE Modern_Spanish_CI_AI)
		   OR (P.apmaterno LIKE @nombrelike  COLLATE Modern_Spanish_CI_AI)
		   OR (P.personaid LIKE @nombrelike  COLLATE Modern_Spanish_CI_AI)
		   OR (P.nombre + ' ' + P.appaterno + ' ' + P.apmaterno LIKE @nombrelike  COLLATE Modern_Spanish_CI_AI)
		   OR (R.Descripcion LIKE @nombrelike  COLLATE Modern_Spanish_CI_AI)
	)ResultadoPaginado
	
	SELECT @totalreg =  @totalreg / @decuantos
	SELECT @vdecimal = @totalreg - convert(integer,  @totalreg)
    
    IF @vdecimal > 0 
		SELECT @total = @totalreg + 1
	ELSE
		SELECT @total = @totalreg
                   
    SET @totalreg = @totalreg * @decuantos
                                                                  
   	select  @total as total, @totalreg as totalreg
                                                                  
    RETURN
		
END
GO
