USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_feriados_listado]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Macarena Parra Bruna
-- Creado el: 14/06/2018
-- Descripcion: Genera todos los registros
-- =============================================
CREATE PROCEDURE [dbo].[sp_feriados_listado]
AS
BEGIN
	
    SELECT idFeriado,CONVERT(VARCHAR(10),Fecha,105)  AS Feriado, Descripcion     
    --SELECT idFeriado, CAST(Fecha as Date)  AS Feriado, Descripcion     
    FROM Feriados  
    ORDER BY Fecha  
    RETURN                                                             

END
GO
