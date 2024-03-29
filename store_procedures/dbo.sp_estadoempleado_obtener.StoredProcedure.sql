USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_estadoempleado_obtener]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 20/07/2019
-- Descripcion:  Lista los estados del empleado disponibles 
-- Ejemplo:exec sp_estadoempleado_obtener
-- =============================================
CREATE PROCEDURE [dbo].[sp_estadoempleado_obtener]
	@idestadoempleado INT
AS
BEGIN

	SET NOCOUNT ON;	
	
   SELECT	
		idEstadoEmpleado,
		Descripcion
	FROM
		EstadosEmpleados
   WHERE
		idEstadoEmpleado = @idestadoempleado
                       
    RETURN                                                             

END
GO
