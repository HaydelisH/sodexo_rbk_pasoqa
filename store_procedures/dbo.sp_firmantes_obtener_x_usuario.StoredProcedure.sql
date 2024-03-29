USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_firmantes_obtener_x_usuario]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Cristian Soto 
-- Creado el: 20/08/2018
-- Descripcion: Obtener información de firmantes por rut
-- Ejemplo:sp_firmantes_obtener_x_usuario '11111111-1'
-- =============================================
CREATE PROCEDURE [dbo].[sp_firmantes_obtener_x_usuario]
	@firmanteid varchar(10)
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;


	SELECT 
	 Firmantes.RutEmpresa
	,Firmantes.RutUsuario
	,U.idFirma
	,ISNULL(Firmantes.tienerol,0) as tienerol
	,FT.Descripcion
	--,Empresas.
	FROM Firmantes 
	INNER JOIN Empresas ON Firmantes.RutEmpresa = Empresas.RutEmpresa
	INNER JOIN Usuarios U ON U.usuarioid = Firmantes.RutUsuario
	INNER JOIN Firmas FT ON FT.idFirma = U.idFirma
	WHERE Firmantes.RutUsuario = @firmanteid

END
GO
