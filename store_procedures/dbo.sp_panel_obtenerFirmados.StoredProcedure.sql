USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_panel_obtenerFirmados]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez 
-- Creado el: 25/09/2017
-- Descripcion:  Documentos por meses y en proceso 
-- Ejemplo:exec sp_panel_obtenerFirmados
-- =============================================
CREATE PROCEDURE [dbo].[sp_panel_obtenerFirmados]
 @usuarioid VARCHAR (10)      
AS    
BEGIN
		
		DECLARE @FechaInicio datetime
		SET @FechaInicio = DATEADD(MONTH, -12,GETDATE());

		With DocumentosTabla as 
					(
					SELECT 
						  MONTH(FechaCreacion) As Meses,
						  YEAR(FechaCreacion) As Annos,
						  CASE idEstado           
								WHEN 2 THEN 'P'
								WHEN 3 THEN 'P'
								WHEN 4 THEN 'P'
								WHEN 5 THEN 'P'
								WHEN 6 THEN 'F'
						  END AS Estado
		                  
					FROM 
						  Contratos 
					WHERE 
						   YEAR(FechaCreacion) < @FechaInicio --Fecha de Creacion es de este año
			  )
			  SELECT Annos, Meses, Estado, COUNT(Estado) As Total_Documentos FROM DocumentosTabla 
			  GROUP BY Meses,Estado, Annos

      RETURN
END
GO
