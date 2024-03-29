USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_documentos_obtenerCuantos_PorEjecutivo]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 31/10/2018
-- Descripcion: Obtiene cuantos contratos hay un mes
-- Ejemplo:exec [sp_documentos_obtenerCuantos_PorEjecutivo] 
-- =============================================
CREATE PROCEDURE [dbo].[sp_documentos_obtenerCuantos_PorEjecutivo]
	@RutEjecutivo VARCHAR(10),
	@RutEmpresa	  VARCHAR(10)
AS
BEGIN	
	SET NOCOUNT ON;			
   
    BEGIN
    With DocumentosTabla as 
		(
		SELECT 
			CASE C.idEstado
				WHEN 1 THEN 'Pendiente'
				WHEN 2 THEN 'Proceso'
				WHEN 3 THEN 'Proceso'
				WHEN 4 THEN 'Proceso'
				WHEN 5 THEN 'Proceso'
				WHEN 8 THEN 'Rechazados'
				WHEN 6 THEN 'Firmados'
				
			END AS Estado, 
			COUNT(C.idContrato) As Cantidad 
		FROM 
			Contratos C
			INNER JOIN DocumentosVariables DV	ON DV.idDocumento = C.idContrato
			INNER JOIN Ejecutivos E				ON E.RutCliente   =	DV.RutCliente
		WHERE 
			E.RutEjecutivo = @RutEjecutivo AND E.RutEmpresa = @RutEmpresa
			Group by idEstado 
		)				
		select Estado,Sum(Cantidad) as Total  from DocumentosTabla
				Group By  Estado 	
	END
END
GO
