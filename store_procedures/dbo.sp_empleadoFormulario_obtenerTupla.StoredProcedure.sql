USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_empleadoFormulario_obtenerTupla]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Cristian Soto
-- Creado el: 08/08/2018
-- Descripcion: Eliminar resultado importacion
-- Ejemplo:exec sp_confImp_eliminar '9798215-5'
-- =============================================
CREATE PROCEDURE [dbo].[sp_empleadoFormulario_obtenerTupla]
    @empleadoFormularioid  		INT
AS
BEGIN
	SET NOCOUNT ON;
	
	DECLARE @mensaje	VARCHAR(100)
	DECLARE @error		INT

    SELECT 
        empleadoFormulario.estadoFormularioid AS idEstadoGestion
    FROM 
        empleadoFormulario
    WHERE
        empleadoFormulario.empleadoFormularioid = @empleadoFormularioid
    --ORDER BY
    --    empleadoFormulario.fechaCarga DESC
								
	SELECT @error= 0
	SELECT @mensaje = ''	
	
	SELECT @error AS error, @mensaje AS mensaje;
  
END
GO
