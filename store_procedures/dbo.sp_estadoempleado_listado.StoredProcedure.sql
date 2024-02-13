USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_estadoempleado_listado]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 20/07/2019
-- Descripcion:  Lista los estados del empleado disponibles 
-- Ejemplo:exec sp_estadoempleado_listado
-- =============================================
CREATE PROCEDURE [dbo].[sp_estadoempleado_listado]
AS
BEGIN
	
   SELECT	
		idEstadoEmpleado,
		Descripcion
	FROM
		EstadosEmpleados
                         
    RETURN                                                             

END
GO
