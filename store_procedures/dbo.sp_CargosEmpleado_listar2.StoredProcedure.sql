USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_CargosEmpleado_listar2]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[sp_CargosEmpleado_listar2]
AS
BEGIN
	SET NOCOUNT ON;
	BEGIN
         SELECT
            CargosEmpresa.RutEmpresa,
            CargosEmpleado.idCargoEmpleado,
            CargosEmpleado.idCargoEmpleado,
            CargosEmpleado.Titulo
        FROM CargosEmpleado
        INNER JOIN CargosEmpresa
            ON CargosEmpresa.idCargoEmpleado = CargosEmpleado.idCargoEmpleado
    END;
END;
GO
